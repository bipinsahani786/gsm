<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    public function bindIndex()
    {
        $bank = BankDetail::where('user_id', Auth::id())->where('type', 'bank')->first();
        $crypto = BankDetail::where('user_id', Auth::id())->where('type', 'crypto')->first();
        return view('backend.users.pages.bind_account', compact('bank', 'crypto'));
    }

    public function bindStore(Request $request)
    {
        $request->validate(['type' => 'required|in:bank,crypto']);

        // Update or Create data
        BankDetail::updateOrCreate(
            ['user_id' => Auth::id(), 'type' => $request->type],
            $request->only(['account_holder', 'account_number', 'ifsc_code', 'bank_name', 'wallet_address', 'network'])
        );

        return back()->with('success', strtoupper($request->type) . ' details updated successfully!');
    }
}
