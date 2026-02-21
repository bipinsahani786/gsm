<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserWithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWithdrawController extends Controller
{
    protected $withdrawService;

    public function __construct(UserWithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    public function index()
    {
        $data = $this->withdrawService->getWithdrawData(Auth::user());
        return view('backend.users.pages.withdraw', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'wallet_type' => 'required|in:personal,income',
            'method' => 'required|in:bank,crypto'
        ]);

        $result = $this->withdrawService->processWithdraw(Auth::user(), $request->all());

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('user.withdraw.history')->with('success', $result['message']);
    }

    public function history()
    {
        $history = $this->withdrawService->getHistory(Auth::user());
        return view('backend.users.pages.withdraw_history', compact('history'));
    }
}