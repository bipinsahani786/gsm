<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Configuration;
use App\Models\IncomeMethod;
use App\Models\Level;
use App\Models\Popup;
use App\Models\Transaction;
use App\Models\UpgradeReward;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserDashboardService
{
    public function getDashboardData(User $user)
    {
        $wallet = $user->wallet;
        $totalAssets = $wallet ? ($wallet->personal_wallet + $wallet->income_wallet) : 0;
        $levels = Level::where('status', 1)->get();
        $positionName = $user->position ? $user->position->name : 'No Position';

        $earningTypes = [
            'task_income', 'referral_commission', 'team_task_commission',
            'monthly_salary', 'rank_bonus', 'promotional_bonus'
        ];

        $today = Carbon::today('Asia/Kolkata');
        $yesterday = Carbon::yesterday('Asia/Kolkata');
        $startOfWeek = Carbon::now('Asia/Kolkata')->startOfWeek();
        $endOfWeek = Carbon::now('Asia/Kolkata')->endOfWeek();

        return [
            'wallet' => $wallet,
            'totalAssets' => $totalAssets,
            'positionName' => $positionName,
            'levels' => $levels,
            'todayEarning' => Transaction::where('user_id', $user->id)->whereIn('type', $earningTypes)->whereDate('created_at', $today)->sum('amount'),
            'yesterdayEarning' => Transaction::where('user_id', $user->id)->whereIn('type', $earningTypes)->whereDate('created_at', $yesterday)->sum('amount'),
            'thisWeekEarning' => Transaction::where('user_id', $user->id)->whereIn('type', $earningTypes)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('amount'),
            'thisMonthEarning' => Transaction::where('user_id', $user->id)->whereIn('type', $earningTypes)->whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->sum('amount'),
            'teamTaskCommission' => Transaction::where('user_id', $user->id)->where('type', 'team_task_commission')->sum('amount'),
            'recommendedIncome' => Transaction::where('user_id', $user->id)->where('type', 'referral_commission')->sum('amount'),
            'totalRevenue' => Transaction::where('user_id', $user->id)->whereIn('type', $earningTypes)->sum('amount'),
            'activePopup' => Popup::where('status', 1)->latest()->first()
        ];
    }

    public function processLevelJoin(User $user, $levelId)
    {
        $newLevel = Level::find($levelId);
        if (!$newLevel) return ['status' => false, 'message' => 'Level not found'];

        $wallet = $user->wallet;
        if ($user->level_id == $newLevel->id) {
            return ['status' => false, 'message' => 'You are already on this level!'];
        }

        $oldLevel = null;
        $refundAmount = 0;

        if ($user->level_id) {
            $oldLevel = Level::find($user->level_id);
            if ($oldLevel) {
                if ($newLevel->min_deposit < $oldLevel->min_deposit) {
                    return ['status' => false, 'message' => 'You cannot downgrade to a lower level.'];
                }
                $refundAmount = $oldLevel->min_deposit;
            }
        }

        $effectiveBalance = $wallet->personal_wallet + $refundAmount;

        if ($effectiveBalance < $newLevel->min_deposit) {
            $shortage = $newLevel->min_deposit - $effectiveBalance;
            return ['status' => false, 'message' => 'Insufficient balance! Please recharge ₹' . number_format($shortage, 2) . ' more to upgrade.'];
        }

        try {
            DB::beginTransaction();

            if ($oldLevel && $refundAmount > 0) {
                $wallet->increment('personal_wallet', $refundAmount);
                Transaction::create([
                    'user_id' => $user->id, 'amount' => $refundAmount,
                    'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                    'type' => 'plan_refund', 'trx_id' => 'REF' . strtoupper(uniqid()),
                    'details' => "Refund for previous level ({$oldLevel->name})"
                ]);
            }

            $wallet->decrement('personal_wallet', $newLevel->min_deposit);
            $user->level_id = $newLevel->id;
            $user->save();

            Transaction::create([
                'user_id' => $user->id, 'amount' => $newLevel->min_deposit,
                'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                'type' => 'plan_purchase', 'trx_id' => 'PLN' . strtoupper(uniqid()),
                'details' => $oldLevel ? "Upgraded to {$newLevel->name} Level" : "Joined {$newLevel->name} Level"
            ]);

            // Promotional Rewards Logic
            $now = Carbon::now('Asia/Kolkata');
            $promo = UpgradeReward::where('to_level_id', $newLevel->id)
                ->where('from_level_id', $oldLevel ? $oldLevel->id : null)
                ->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->first();

            if ($promo) {
                $wallet->increment('income_wallet', $promo->reward_amount);
                Transaction::create([
                    'user_id' => $user->id, 'amount' => $promo->reward_amount,
                    'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                    'type' => 'promotional_bonus', 'trx_id' => 'PRM' . strtoupper(uniqid()),
                    'details' => "Limited Time Upgrade Bonus to {$newLevel->name}"
                ]);
            }

            // Referral Commission Logic
            if (!$oldLevel && $user->rid) {
                $percentages = [
                    1 => Configuration::get('referral_l1', 10),
                    2 => Configuration::get('referral_l2', 5),
                    3 => Configuration::get('referral_l3', 2)
                ];
                $currentSponsor = $user->sponsor;
                $levelCount = 1;

                while ($currentSponsor && $levelCount <= 3) {
                    $sponsorCap = $currentSponsor->level ? $currentSponsor->level->min_deposit : 0;
                    if ($sponsorCap > 0) {
                        $commissionBaseAmount = min($sponsorCap, $newLevel->min_deposit);
                        $commissionAmount = ($commissionBaseAmount * $percentages[$levelCount]) / 100;
                        
                        if ($commissionAmount > 0) {
                            $sponsorWallet = $currentSponsor->wallet;
                            $sponsorWallet->increment('income_wallet', $commissionAmount);
                            Transaction::create([
                                'user_id' => $currentSponsor->id, 'amount' => $commissionAmount,
                                'post_balance' => $sponsorWallet->personal_wallet + $sponsorWallet->income_wallet,
                                'type' => 'referral_commission', 'trx_id' => 'REF' . strtoupper(uniqid()),
                                'details' => "Level {$levelCount} commission from {$user->name} joining {$newLevel->name}"
                            ]);
                        }
                    }
                    $currentSponsor = $currentSponsor->sponsor;
                    $levelCount++;
                }
            }

            DB::commit();
            $successMsg = $oldLevel ? "Upgraded to {$newLevel->name} successfully." : "Joined {$newLevel->name} successfully.";
            return ['status' => true, 'message' => $successMsg];

        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'message' => 'Transaction failed: ' . $e->getMessage()];
        }
    }

    public function getGuideData()
    {
        return [
            'banners' => Banner::where('status', 1)->latest()->get(),
            'methods' => IncomeMethod::where('status', 1)->latest()->get()
        ];
    }
}