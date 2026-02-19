<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.admins.pages.deposits.index', compact('deposits'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            $deposit = Deposit::findOrFail($id);
            if ($deposit->status !== 'pending')
                return back()->with('error', 'Already processed');

            $user = User::find($deposit->user_id);

            // 1. Update Wallet
            $user->wallet->increment('personal_wallet', $deposit->amount);

            // 2. Create Transaction
            $transaction = new \App\Models\Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $user->wallet->personal_wallet + $user->wallet->income_wallet;
            $transaction->type = 'deposit';
            $transaction->trx_id = 'DEP' . strtoupper(uniqid()); // Ek unique ID generate karein
            $transaction->details = "Deposit of ₹" . $deposit->amount . " approved via " . strtoupper($deposit->method);
            $transaction->save();

            $deposit->update(['status' => 'approved', 'remarks' => 'Payment Verified']);

            DB::commit();
            return back()->with('success', 'Approved and Transaction Recorded!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['remarks' => 'required|string|max:255']);
        $deposit = Deposit::findOrFail($id);

        $deposit->update([
            'status' => 'rejected',
            'remarks' => $request->remarks
        ]);

        return back()->with('success', 'Deposit Rejected with Remarks');
    }
}
