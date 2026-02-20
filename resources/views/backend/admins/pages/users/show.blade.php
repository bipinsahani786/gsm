@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">User Details</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Reviewing 360° Profile</p>
        </div>
        <a href="{{ url()->previous() }}" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-colors">
            &larr; Go Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
            
            <div class="flex items-center gap-4 relative z-10 mb-6">
                <div class="w-16 h-16 rounded-full bg-slate-100 border-2 border-white shadow-md flex items-center justify-center text-xl font-black text-slate-400 shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $user->name }}</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">UID: <span class="text-slate-700">{{ $user->uid }}</span></p>
                    <div class="flex gap-2 mt-1">
                        @if($user->level_id)
                            <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest">{{ $user->level->name }}</span>
                        @else
                            <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest">Free User</span>
                        @endif
                        @if($user->position_id)
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest">{{ $user->position->name }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-3 relative z-10">
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mobile</span>
                    <span class="text-[11px] font-black text-slate-700">{{ $user->mobile ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Joined</span>
                    <span class="text-[11px] font-black text-slate-700">{{ $user->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sponsor</span>
                    @if($sponsor)
                        <span class="text-[11px] font-black text-blue-600 underline">{{ $sponsor->name }} ({{ $sponsor->uid }})</span>
                    @else
                        <span class="text-[11px] font-black text-slate-400">Direct Joined</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Financial Overview</h3>
            
            <div class="mb-5">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Available Balance</p>
                <h2 class="text-3xl font-black">₹{{ number_format(optional($user->wallet)->income_wallet + optional($user->wallet)->personal_wallet, 2) }}</h2>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 p-3 rounded-2xl border border-white/5">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Income Wallet</p>
                    <p class="text-sm font-black mt-1">₹{{ number_format(optional($user->wallet)->income_wallet, 2) }}</p>
                </div>
                <div class="bg-white/10 p-3 rounded-2xl border border-white/5">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Personal Wallet</p>
                    <p class="text-sm font-black mt-1">₹{{ number_format(optional($user->wallet)->personal_wallet, 2) }}</p>
                </div>
                <div class="bg-white/10 p-3 rounded-2xl border border-white/5 col-span-2 text-center">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lifetime Earnings</p>
                    <p class="text-lg font-black mt-1 text-emerald-400">₹{{ number_format(optional($user->wallet)->total_wallet, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Network Stats</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Direct Members</span>
                        <span class="text-lg font-black text-blue-600">{{ $directTeam }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Team (L1+L2+L3)</span>
                        <span class="text-lg font-black text-indigo-600">{{ $totalTeam }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Active Team</span>
                        <span class="text-lg font-black text-emerald-600">{{ $activeTeam }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.users.tree', $user->id) }}" class="w-full bg-slate-900 text-white mt-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-slate-800 transition-colors">
                View Network Tree
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <h3 class="font-black text-xs text-slate-800 uppercase tracking-[0.2em] mb-4">Saved Payment Methods</h3>
                <div class="space-y-3">
                    @forelse($bankDetails as $bank)
                        <div class="p-3 border border-slate-100 rounded-xl bg-slate-50">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest">{{ strtoupper($bank->type) }}</span>
                                @if($bank->type == 'bank')
                                    <span class="text-[10px] font-black text-blue-600">{{ $bank->bank_name }}</span>
                                @else
                                    <span class="text-[10px] font-black text-amber-600">{{ $bank->network }}</span>
                                @endif
                            </div>
                            @if($bank->type == 'bank')
                                <p class="text-xs font-bold text-slate-500">A/c: {{ $bank->account_number }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">Name: {{ $bank->account_holder_name }} | IFSC: {{ $bank->ifsc_code }}</p>
                            @else
                                <p class="text-xs font-bold text-slate-500 break-all">{{ $bank->wallet_address }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center py-4">No Payment Method Saved</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-slate-50 p-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-xs text-slate-800 uppercase tracking-[0.2em]">Recent Withdrawals</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($withdrawals as $withdraw)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <p class="text-xs font-black text-slate-800">₹{{ number_format($withdraw->amount, 2) }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $withdraw->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div>
                                @if($withdraw->status == 'pending')
                                    <span class="bg-amber-50 text-amber-600 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest">Pending</span>
                                @elseif($withdraw->status == 'approved')
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest">Approved</span>
                                @else
                                    <span class="bg-red-50 text-red-600 px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-[10px] font-bold uppercase tracking-widest">No Withdrawals Yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden h-fit">
            <div class="bg-slate-50 p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-xs text-slate-800 uppercase tracking-[0.2em]">Transaction Passbook</h3>
                <a href="#" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View Full Log</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($transactions as $trx)
                    <div class="p-4 px-6 flex justify-between items-center hover:bg-slate-50 transition-colors">
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
                                <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $trx->created_at->format('d M y, h:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black {{ $trx->type == 'withdraw' ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $trx->type == 'withdraw' ? '-' : '+' }}₹{{ number_format($trx->amount) }}
                            </p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Bal: ₹{{ number_format($trx->post_balance) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">No Transactions Found</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection