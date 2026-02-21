<?php

namespace App\Http\Controllers\Backend\Users\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserRechargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRechargeController extends Controller
{
    protected $rechargeService;

    public function __construct(UserRechargeService $rechargeService)
    {
        $this->rechargeService = $rechargeService;
    }

    public function index()
    {
        $data = $this->rechargeService->getRechargeConfig();
        return view('backend.users.pages.recharge', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'method' => 'required|in:upi,usdt',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $result = $this->rechargeService->submitRecharge(Auth::user(), $request->all(), $request->file('screenshot'));

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function history()
    {
        $deposits = $this->rechargeService->getRechargeHistory(Auth::user());
        return view('backend.users.pages.recharge_history', compact('deposits'));
    }
}