<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Level;
use App\Models\Transaction;
use App\Models\UpgradeReward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function profile()
    {
        return view('backend.users.pages.profile');
    }

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Total Assets
        $totalAssets = $wallet ? ($wallet->personal_wallet + $wallet->income_wallet) : 0;

        // Fetching Active Levels from Database
        $levels = Level::where('status', 1)->get();

        // Assuming user table has `position_id` relation, otherwise default to "General Employee"
        $positionName = $user->position ? $user->position->name : 'No Position';

        return view('backend.users.pages.dashboard', compact('user', 'wallet', 'totalAssets', 'levels', 'positionName'));
    }

    public function joinLevel(Request $request, $id)
    {
        $newLevel = Level::findOrFail($id);
        $user = Auth::user();
        $wallet = $user->wallet;

        // 1. Check if the user is already on the requested level
        if ($user->level_id == $newLevel->id) {
            return back()->with('error', 'You are already on this level!');
        }

        $oldLevel = null;
        $refundAmount = 0;

        // 2. Process existing level (if user is upgrading/downgrading)
        if ($user->level_id) {
            $oldLevel = Level::find($user->level_id);
            if ($oldLevel) {
                // Prevent downgrading to a lower-priced level
                if ($newLevel->min_deposit < $oldLevel->min_deposit) {
                    return back()->with('error', 'You cannot downgrade to a lower level.');
                }

                // Set the refund amount based on the current level's price
                $refundAmount = $oldLevel->min_deposit;
            }
        }

        // 3. Balance Validation
        // Effective balance includes current wallet balance + the refund from the old level
        $effectiveBalance = $wallet->personal_wallet + $refundAmount;

        if ($effectiveBalance < $newLevel->min_deposit) {
            $shortage = $newLevel->min_deposit - $effectiveBalance;
            return back()->with('error', 'Insufficient balance! Please recharge ₹' . number_format($shortage, 2) . ' more to upgrade.');
        }

        try {
            DB::beginTransaction();

            // 4. STEP 1: Refund the old level amount (if applicable)
            if ($oldLevel && $refundAmount > 0) {
                $wallet->increment('personal_wallet', $refundAmount);

                // Log the refund transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $refundAmount,
                    'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                    'type' => 'plan_refund',
                    'trx_id' => 'REF' . strtoupper(uniqid()),
                    'details' => "Refund for previous level ({$oldLevel->name})"
                ]);
            }

            // 5. STEP 2: Deduct the new level's full price
            $wallet->decrement('personal_wallet', $newLevel->min_deposit);

            // 6. Update User's Level
            $user->level_id = $newLevel->id;
            $user->save();

            // 7. Log the purchase transaction
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $newLevel->min_deposit,
                'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                'type' => 'plan_purchase',
                'trx_id' => 'PLN' . strtoupper(uniqid()),
                'details' => $oldLevel ? "Upgraded to {$newLevel->name} Level" : "Joined {$newLevel->name} Level"
            ]);

            // ========================================================
            // LOGIC 1: PROMOTIONAL REWARDS (Time-bound Upgrade Bonus)
            // ========================================================
            $now = Carbon::now('Asia/Kolkata');
            $promo = UpgradeReward::where('to_level_id', $newLevel->id)
                ->where('from_level_id', $oldLevel ? $oldLevel->id : null)
                ->where('status', 1)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->first();

            if ($promo) {
                // Credit the bonus to the income wallet
                $wallet->increment('income_wallet', $promo->reward_amount);

                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $promo->reward_amount,
                    'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                    'type' => 'promotional_bonus',
                    'trx_id' => 'PRM' . strtoupper(uniqid()),
                    'details' => "Limited Time Upgrade Bonus to {$newLevel->name}"
                ]);
            }

            // ========================================================
            // LOGIC 2: REFERRAL COMMISSION (One-Time Only + Sponsor Capping)
            // ========================================================

            // Referral commission is distributed ONLY on the FIRST purchase.
            // If $oldLevel is null, it means the user has never purchased a plan before.
            if (!$oldLevel && $user->rid) {

                // Fetch commission percentages from global configuration
                $percentages = [
                    1 => Configuration::get('referral_l1', 10),
                    2 => Configuration::get('referral_l2', 5),
                    3 => Configuration::get('referral_l3', 2)
                ];

                $currentSponsor = $user->sponsor;
                $levelCount = 1;

                // Traverse up to 3 levels in the sponsor hierarchy
                while ($currentSponsor && $levelCount <= 3) {

                    // The sponsor's maximum earning capacity is capped by their own active level's price.
                    $sponsorCap = $currentSponsor->level ? $currentSponsor->level->min_deposit : 0;

                    if ($sponsorCap > 0) { // If sponsor is un-activated (0), they earn nothing

                        // COMMISSION CALCULATION (Capping Logic)
                        // The percentage is applied to the smaller value between the downline's deposit and the sponsor's cap.
                        $commissionBaseAmount = min($sponsorCap, $newLevel->min_deposit);

                        $commissionAmount = ($commissionBaseAmount * $percentages[$levelCount]) / 100;

                        if ($commissionAmount > 0) {
                            $sponsorWallet = $currentSponsor->wallet;
                            $sponsorWallet->increment('income_wallet', $commissionAmount);

                            Transaction::create([
                                'user_id' => $currentSponsor->id,
                                'amount' => $commissionAmount,
                                'post_balance' => $sponsorWallet->personal_wallet + $sponsorWallet->income_wallet,
                                'type' => 'referral_commission',
                                'trx_id' => 'REF' . strtoupper(uniqid()),
                                'details' => "Level {$levelCount} commission from {$user->name} joining {$newLevel->name}"
                            ]);
                        }
                    }

                    // Move to the next upline
                    $currentSponsor = $currentSponsor->sponsor;
                    $levelCount++;
                }
            }

            DB::commit();

            $successMsg = $oldLevel
                ? "Congratulations! You have successfully upgraded to {$newLevel->name}."
                : "Congratulations! You have successfully joined {$newLevel->name}.";

            return back()->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }
}
