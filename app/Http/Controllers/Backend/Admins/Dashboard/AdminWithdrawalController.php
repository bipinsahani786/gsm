<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.admins.pages.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate(['transaction_id' => 'required|string']);

        try {
            DB::beginTransaction();
            $withdraw = Withdrawal::findOrFail($id);

            if ($withdraw->status !== 'pending')
                return back()->with('error', 'Already processed');

            // Status Update
            $withdraw->update([
                'status' => 'approved',
                'transaction_id' => $request->transaction_id,
                'admin_remark' => 'Payout Processed Successfully'
            ]);

            // Transaction Record (Passbook entry)
            Transaction::create([
                'user_id' => $withdraw->user_id,
                'amount' => $withdraw->amount,
                'post_balance' => $withdraw->user->wallet->personal_wallet + $withdraw->user->wallet->income_wallet,
                'type' => 'withdrawal',
                'trx_id' => 'WDR' . strtoupper(uniqid()),
                'details' => "Withdrawal of ₹{$withdraw->amount} approved via " . strtoupper($withdraw->method)
            ]);

            DB::commit();
            return back()->with('success', 'Withdrawal marked as Approved!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['admin_remark' => 'required|string']);

        try {
            DB::beginTransaction();
            $withdraw = Withdrawal::findOrFail($id);

            // 1. Refund
            $user = $withdraw->user;
            if ($withdraw->wallet_type == 'personal') {
                $user->wallet->increment('personal_wallet', $withdraw->amount);
            } else {
                $user->wallet->increment('income_wallet', $withdraw->amount);
            }

            // 2. Status update
            $withdraw->update([
                'status' => 'rejected',
                'admin_remark' => $request->admin_remark
            ]);

            DB::commit();
            return back()->with('success', 'Withdrawal Rejected & Amount Refunded!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
