<?php

namespace App\Http\Controllers\Backend\Admins\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['wallet', 'level']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('uid', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
        }


        $users = $query->latest()->paginate(15);

        return view('backend.admins.pages.users.index', compact('users'));
    }

    public function show($id)
    {
        // 1. User Details
        $user = User::with(['wallet', 'level', 'position'])->findOrFail($id);

        // 2. Sponsor (Upline)
        $sponsor = User::where('uid', $user->rid)->first();

        // 3. Team Calculations (L1 + L2 + L3)
        $l1Uids = User::where('rid', $user->uid)->pluck('uid');
        $l2Uids = User::whereIn('rid', $l1Uids)->pluck('uid');

        $directTeam = $l1Uids->count();
        $totalTeam = User::where(function ($query) use ($user, $l1Uids, $l2Uids) {
            $query->where('rid', $user->uid)
                ->orWhereIn('rid', $l1Uids)
                ->orWhereIn('rid', $l2Uids);
        })->count();

        $activeTeam = User::whereNotNull('level_id')
            ->where(function ($query) use ($user, $l1Uids, $l2Uids) {
                $query->where('rid', $user->uid)
                    ->orWhereIn('rid', $l1Uids)
                    ->orWhereIn('rid', $l2Uids);
            })->count();

        // 4. Payment Methods (Bank & Crypto)
        $bankDetails = BankDetail::where('user_id', $user->id)->get();

        // 5. Recent Transactions & Withdrawals
        $transactions = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->take(5)->get();

        return view('backend.admins.pages.users.show', compact(
            'user',
            'sponsor',
            'directTeam',
            'totalTeam',
            'activeTeam',
            'bankDetails',
            'transactions',
            'withdrawals'
        ));
    }

    public function tree($id)
    {
        // 1. User Details & Position
        $user = User::with(['nestedDownlines', 'level', 'position'])->findOrFail($id);
        
        return view('backend.admins.pages.users.tree', compact('user'));
    }
}
