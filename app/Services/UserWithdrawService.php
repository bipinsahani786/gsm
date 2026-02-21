<?php

namespace App\Services;

use App\Models\BankDetail;
use App\Models\Configuration;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserWithdrawService
{
    // Withdrawal Page Data Load Karna
    public function getWithdrawData(User $user)
    {
        $config = Configuration::whereIn('key', [
            'min_withdraw_personal', 'min_withdraw_income', 'withdraw_commission',
            'daily_withdraw_limit', 'max_withdraw_limit', 'max_withdraw_daily'
        ])->pluck('value', 'key');

        $bank = BankDetail::where('user_id', $user->id)->where('type', 'bank')->first();
        $crypto = BankDetail::where('user_id', $user->id)->where('type', 'crypto')->first();

        return [
            'settings' => $config,
            'bank' => $bank,
            'crypto' => $crypto
        ];
    }

    // Withdrawal Request Process Karna (Saare Checks ke sath)
    public function processWithdraw(User $user, array $data)
    {
        $amount = $data['amount'];
        $walletType = $data['wallet_type'];
        $method = $data['method'];

        $config = Configuration::pluck('value', 'key');
        $wallet = $user->wallet;

        // --- 1. Wallet Balance Check ---
        $currentBalance = ($walletType == 'personal') ? $wallet->personal_wallet : $wallet->income_wallet;
        if ($amount > $currentBalance) {
            return ['status' => false, 'message' => "Insufficient funds in your {$walletType} wallet."];
        }

        // --- 2. Minimum Limit Check ---
        $minLimit = ($walletType == 'personal') ? ($config['min_withdraw_personal'] ?? 0) : ($config['min_withdraw_income'] ?? 0);
        if ($amount < $minLimit) {
            return ['status' => false, 'message' => "Minimum withdrawal limit for this wallet is ₹{$minLimit}."];
        }

        // --- 3. Maximum Limit Check ---
        $maxLimit = $config['max_withdraw_limit'] ?? 999999;
        if ($amount > $maxLimit) {
            return ['status' => false, 'message' => "Maximum allowed per transaction is ₹{$maxLimit}."];
        }

        // --- 4. Daily Count Check ---
        $today = now()->startOfDay();
        $dailyCountLimit = $config['daily_withdraw_limit'] ?? 1;
        $todayCount = Withdrawal::where('user_id', $user->id)->where('created_at', '>=', $today)->count();
        if ($todayCount >= $dailyCountLimit) {
            return ['status' => false, 'message' => "You have reached your daily withdrawal count limit ({$dailyCountLimit})."];
        }

        // --- 5. Daily Total Amount Check ---
        $dailyMaxAmount = $config['max_withdraw_daily'] ?? 999999;
        $todaySum = Withdrawal::where('user_id', $user->id)->where('created_at', '>=', $today)->where('status', '!=', 'rejected')->sum('amount');
        if (($todaySum + $amount) > $dailyMaxAmount) {
            return ['status' => false, 'message' => "Daily total withdrawal limit is ₹{$dailyMaxAmount}. You have already requested ₹{$todaySum}."];
        }

        // --- 6. Account Binding Check ---
        $account = BankDetail::where('user_id', $user->id)->where('type', $method)->first();
        if (!$account) {
            return ['status' => false, 'message' => "Please bind your " . strtoupper($method) . " details first."];
        }

        // --- 7. Commission Calculation ---
        $commissionPercent = $config['withdraw_commission'] ?? 0;
        $fee = ($amount * $commissionPercent) / 100;
        $finalAmount = $amount - $fee;

        try {
            DB::beginTransaction();

            if ($walletType == 'personal') {
                $wallet->decrement('personal_wallet', $amount);
            } else {
                $wallet->decrement('income_wallet', $amount);
            }

            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'wallet_type' => $walletType,
                'method' => $method,
                'amount' => $amount,
                'fee' => $fee,
                'final_amount' => $finalAmount,
                'account_details' => json_encode($account),
                'status' => 'pending'
            ]);

            DB::commit();
            return ['status' => true, 'message' => 'Withdrawal request submitted successfully!', 'data' => $withdrawal];

        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'message' => 'Something went wrong: ' . $e->getMessage()];
        }
    }

    // Withdrawal History
    public function getHistory(User $user)
    {
        return Withdrawal::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    }
}