@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex justify-between items-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Admin Overview</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Welcome back, Boss! Here's what's happening today.</p>
        </div>
        <div class="relative z-10 text-right">
            <p class="text-xs font-black text-slate-800 bg-slate-100 px-4 py-2 rounded-xl">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</p>
        </div>
    </div>

    @if($pendingWithdrawCount > 0)
    <div class="bg-red-50 border border-red-100 p-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm animate-pulse">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-red-700 font-black text-sm uppercase tracking-tight">Action Required</h4>
                <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest">You have {{ $pendingWithdrawCount }} pending withdrawal requests.</p>
            </div>
        </div>
        <a href="#" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-red-700 transition-colors">
            Review Now
        </a>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
                <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalUsers) }}</h3>
                <p class="text-[9px] font-bold text-emerald-500 mt-1 uppercase">+{{ $todayJoined }} Today</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Members</p>
                <h3 class="text-2xl font-black text-slate-800">{{ number_format($activeUsers) }}</h3>
                <p class="text-[9px] font-bold text-amber-500 mt-1 uppercase">{{ $totalPositionsDistributed }} Ranked</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Deposits</p>
                <h3 class="text-2xl font-black text-slate-800">₹{{ number_format($totalRecharge) }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Payouts</p>
                <h3 class="text-2xl font-black text-slate-800">₹{{ number_format($totalWithdraw) }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="bg-slate-50 p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-xs text-slate-800 uppercase tracking-[0.2em]">New Members</h3>
                <a href="#" class="text-[10px] font-bold text-amber-600 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentUsers as $u)
                <div class="p-4 px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 font-black text-xs flex items-center justify-center shrink-0">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $u->name }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">UID: {{ $u->uid }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($u->level_id)
                            <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest">Active</span>
                        @else
                            <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest">Free</span>
                        @endif
                        <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase">{{ $u->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">No users yet</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="bg-slate-50 p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-xs text-slate-800 uppercase tracking-[0.2em]">Recent Transactions</h3>
                <a href="#" class="text-[10px] font-bold text-amber-600 uppercase tracking-widest hover:underline">Passbook</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentTransactions as $trx)
                <div class="p-4 px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $trx->type == 'withdraw' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                            @if($trx->type == 'withdraw')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 uppercase tracking-tight">{{ str_replace('_', ' ', $trx->type) }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $trx->user->name ?? 'User' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black {{ $trx->type == 'withdraw' ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $trx->type == 'withdraw' ? '-' : '+' }}₹{{ number_format($trx->amount) }}
                        </p>
                        @if($trx->status == 1)
                            <p class="text-[8px] font-bold text-emerald-500 mt-1 uppercase tracking-widest">Success</p>
                        @elseif($trx->status == 0)
                            <p class="text-[8px] font-bold text-amber-500 mt-1 uppercase tracking-widest">Pending</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">No transactions yet</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection