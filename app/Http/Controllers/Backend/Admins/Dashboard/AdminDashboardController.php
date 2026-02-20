<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Kolkata');

        // 1. User Stats
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('level_id')->count();
        $todayJoined = User::whereDate('created_at', $today)->count();

        // 2. Financial Stats (Recharge & Withdraw)
        $totalRecharge = Transaction::where('type', 'deposit')->sum('amount');
        $totalWithdraw = Transaction::where('type', 'withdrawal')->sum('amount');
        
        $pendingWithdrawCount = Transaction::where('type', 'withdrawal')->count();

        // 3. Position/Rank Stats
        $totalPositionsDistributed = User::whereNotNull('position_id')->count();

        // 4. Recent Data for Tables
        $recentUsers = User::latest()->take(5)->get();
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        // View ko data pass karna
        return view('backend.admins.pages.dashboard', compact(
            'totalUsers', 'activeUsers', 'todayJoined',
            'totalRecharge', 'totalWithdraw', 'pendingWithdrawCount',
            'totalPositionsDistributed', 'recentUsers', 'recentTransactions'
        ));
    }
}
