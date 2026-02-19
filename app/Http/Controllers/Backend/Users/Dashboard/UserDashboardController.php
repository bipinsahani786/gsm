<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Transaction;
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

        // 1. Check if user already has this level
        if ($user->level_id == $newLevel->id) {
            return back()->with('error', 'You are already on this level!');
        }

        $oldLevel = null;
        $refundAmount = 0;

        // 2. Check if user is downgrading
        if ($user->level_id) {
            $oldLevel = Level::find($user->level_id);
            if ($oldLevel) {
                // Downgrade check (Bade level se chote level me nahi ja sakta)
                if ($newLevel->min_deposit < $oldLevel->min_deposit) {
                    return back()->with('error', 'You cannot downgrade to a lower level.');
                }
                
                // Refund amount set karo
                $refundAmount = $oldLevel->min_deposit;
            }
        }

        // 3. Balance Check 
        $effectiveBalance = $wallet->personal_wallet + $refundAmount;
        
        if ($effectiveBalance < $newLevel->min_deposit) {
            $shortage = $newLevel->min_deposit - $effectiveBalance;
            return back()->with('error', 'Insufficient balance! Please recharge ₹' . number_format($shortage, 2) . ' more to upgrade.');
        }

        try {
            DB::beginTransaction();

            // 4. STEP 1: Refund old level amount
            if ($oldLevel && $refundAmount > 0) {
                $wallet->increment('personal_wallet', $refundAmount);
                
                // Transaction record
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $refundAmount,
                    'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                    'type' => 'plan_refund',
                    'trx_id' => 'REF' . strtoupper(uniqid()),
                    'details' => "Refund for previous level ({$oldLevel->name})"
                ]);
            }

            // 5. STEP 2: Naye level ka amount cut karo
            $wallet->decrement('personal_wallet', $newLevel->min_deposit);

            // 6. User ka Level Update karo
            $user->level_id = $newLevel->id;
            $user->save();

            // 7. Naye level purchase ka transaction record
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $newLevel->min_deposit,
                'post_balance' => $wallet->personal_wallet + $wallet->income_wallet,
                'type' => 'plan_purchase',
                'trx_id' => 'PLN' . strtoupper(uniqid()),
                'details' => $oldLevel ? "Upgraded to {$newLevel->name} Level" : "Joined {$newLevel->name} Level"
            ]);

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
