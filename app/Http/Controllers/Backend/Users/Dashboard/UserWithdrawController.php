<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\Configuration;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserWithdrawController extends Controller
{
    /**
     * Withdrawal Page Load
     */
    public function index()
    {
        // Sabhi 6 withdrawal settings fetch karein
        $config = Configuration::whereIn('key', [
            'min_withdraw_personal',
            'min_withdraw_income',
            'withdraw_commission',
            'daily_withdraw_limit',
            'max_withdraw_limit',
            'max_withdraw_daily'
        ])->pluck('value', 'key');

        // User ke saved Payment Methods
        $bank = BankDetail::where('user_id', Auth::id())->where('type', 'bank')->first();
        $crypto = BankDetail::where('user_id', Auth::id())->where('type', 'crypto')->first();

        return view('backend.users.pages.withdraw', [
            'settings' => $config,
            'bank' => $bank,
            'crypto' => $crypto
        ]);
    }

    /**
     * Withdrawal Request Store Logic
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'wallet_type' => 'required|in:personal,income',
            'method' => 'required|in:bank,crypto'
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Database se current settings fetch karein
        $config = Configuration::pluck('value', 'key');

        // --- 1. Wallet Balance Check ---
        $wallet = $user->wallet;
        $currentBalance = ($request->wallet_type == 'personal')
            ? $wallet->personal_wallet
            : $wallet->income_wallet;

        if ($amount > $currentBalance) {
            return back()->with('error', "Insufficient funds in your {$request->wallet_type} wallet.");
        }

        // --- 2. Minimum Transaction Limit Check ---
        $minLimit = ($request->wallet_type == 'personal')
            ? ($config['min_withdraw_personal'] ?? 0)
            : ($config['min_withdraw_income'] ?? 0);

        if ($amount < $minLimit) {
            return back()->with('error', "Minimum withdrawal limit for this wallet is ₹{$minLimit}.");
        }

        // --- 3. Maximum Per Transaction Limit Check ---
        $maxLimit = $config['max_withdraw_limit'] ?? 999999;
        if ($amount > $maxLimit) {
            return back()->with('error', "Maximum allowed per transaction is ₹{$maxLimit}.");
        }

        // --- 4. Daily Withdrawal Count Check ---
        $today = now()->startOfDay();
        $dailyCountLimit = $config['daily_withdraw_limit'] ?? 1;
        $todayCount = Withdrawal::where('user_id', $user->id)
            ->where('created_at', '>=', $today)
            ->count();

        if ($todayCount >= $dailyCountLimit) {
            return back()->with('error', "You have reached your daily withdrawal count limit ({$dailyCountLimit}).");
        }

        // --- 5. Daily Total Amount Check ---
        $dailyMaxAmount = $config['max_withdraw_daily'] ?? 999999;
        $todaySum = Withdrawal::where('user_id', $user->id)
            ->where('created_at', '>=', $today)
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        if (($todaySum + $amount) > $dailyMaxAmount) {
            return back()->with('error', "Daily total withdrawal limit is ₹{$dailyMaxAmount}. You have already requested ₹{$todaySum}.");
        }

        // --- 6. Account Binding Check ---
        $account = BankDetail::where('user_id', $user->id)->where('type', $request->method)->first();
        if (!$account) {
            return back()->with('error', "Please bind your " . strtoupper($request->method) . " details first.");
        }

        // --- 7. Calculation (Commission/Fee) ---
        $commissionPercent = $config['withdraw_commission'] ?? 0;
        $fee = ($amount * $commissionPercent) / 100;
        $finalAmount = $amount - $fee;

        // --- 8. Database Transaction (Deduct & Save) ---
        try {
            DB::beginTransaction();

            // Wallet se paise kaatein
            if ($request->wallet_type == 'personal') {
                $wallet->decrement('personal_wallet', $amount);
            } else {
                $wallet->decrement('income_wallet', $amount);
            }

            // Withdrawal record banayein
            Withdrawal::create([
                'user_id' => $user->id,
                'wallet_type' => $request->wallet_type,
                'method' => $request->method,
                'amount' => $amount,
                'fee' => $fee,
                'final_amount' => $finalAmount,
                'account_details' => json_encode($account), // Snapshot of details
                'status' => 'pending'
            ]);

            DB::commit();
            return redirect()->route('user.withdraw.history')->with('success', 'Withdrawal request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * User Withdrawal History
     */
    public function history()
    {
        $history = Withdrawal::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.users.pages.withdraw_history', compact('history'));
    }
}
