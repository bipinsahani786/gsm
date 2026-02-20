@extends('backend.users.layouts.mobile')

@section('content')
    <div class="min-h-screen bg-[#F8F9FA] pb-28 font-sans overflow-x-hidden">

        <div class="bg-slate-900 pt-6 pb-20 px-5 rounded-b-[2.5rem] shadow-xl relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-48 h-48 bg-red-600/10 rounded-full blur-3xl -mr-12 -mt-12 pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl -ml-12 -mb-12 pointer-events-none">
            </div>

            <div class="relative flex items-center gap-4 z-10">
                <div class="relative shrink-0">
                    <div class="w-16 h-16 rounded-2xl border-2 border-slate-700 overflow-hidden shadow-lg bg-slate-800">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ef4444&color=fff&size=128"
                            class="w-full h-full object-cover">
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-slate-900 animate-pulse">
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-white text-lg font-black tracking-tight truncate">{{ Auth::user()->name }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest truncate">UID:
                            {{ Auth::user()->uid }}</p>
                        
                        <span class="bg-slate-800 border border-slate-700 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <svg class="w-2.5 h-2.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-white text-[8px] font-black uppercase">
                                {{ Auth::user()->level_id ? Auth::user()->level->name : 'FREE MEMBER' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 -mt-14 relative z-20 space-y-4">

            <div
                class="bg-white rounded-[2rem] p-5 shadow-lg shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="flex justify-between items-start mb-5 relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Balance</p>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight truncate">
                            ₹{{ number_format(Auth::user()->wallet->income_wallet + Auth::user()->wallet->personal_wallet, 2) }}
                        </h1>
                    </div>
                    <div class="bg-slate-50 p-2.5 rounded-xl text-slate-300">
                        </div>
                </div>

                <div class="grid grid-cols-2 gap-3 relative z-10">
                    <a href="{{ route('user.recharge') }}"
                        class="bg-slate-900 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest text-center shadow-md active:scale-95 transition-all flex items-center justify-center gap-2 hover:bg-slate-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Recharge
                    </a>
                    <a href="{{ route('user.withdraw') }}"
                        class="bg-red-600 text-white py-3.5 rounded-xl font-black text-[10px] uppercase tracking-widest text-center shadow-md shadow-red-200 active:scale-95 transition-all flex items-center justify-center gap-2 hover:bg-red-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Withdraw
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2.5">
                <div
                    class="bg-white px-2 py-3.5 rounded-[1.5rem] border border-slate-50 shadow-sm text-center flex flex-col items-center justify-center min-h-[90px]">
                    <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">Income Wallet</p>
                    <p class="text-xs font-black text-slate-900 truncate w-full px-1">
                        ₹{{ number_format(Auth::user()->wallet->income_wallet, 2) }}</p>
                </div>

                <div
                    class="bg-white px-2 py-3.5 rounded-[1.5rem] border border-slate-50 shadow-sm text-center flex flex-col items-center justify-center min-h-[90px]">
                    <div class="w-7 h-7 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">Personal Wallet</p>
                    <p class="text-xs font-black text-slate-900 truncate w-full px-1">
                        ₹{{ number_format(Auth::user()->wallet->personal_wallet, 2) }}</p>
                </div>

                <div
                    class="bg-white px-2 py-3.5 rounded-[1.5rem] border border-slate-50 shadow-sm text-center flex flex-col items-center justify-center min-h-[90px]">
                    <div class="w-7 h-7 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">Lifetime Income</p>
                    <p class="text-xs font-black text-slate-900 truncate w-full px-1">
                        ₹{{ number_format(Auth::user()->wallet->total_wallet, 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100">

                @php
                    $itemClass =
                        'flex items-center justify-between p-4 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 active:bg-slate-50';
                    $iconBoxClass = 'w-9 h-9 rounded-xl flex items-center justify-center shrink-0';
                @endphp

                <a href="{{ route('user.team') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="{{ $iconBoxClass }} bg-orange-50 text-orange-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">My Team</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Network stats</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <a href="{{ route('user.bind.index') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="{{ $iconBoxClass }} bg-emerald-50 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">Payment Methods</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Bind Bank & Crypto</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <a href="{{ route('user.recharge.history') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">Deposit Records</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Transaction history</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                
                <a href="{{ route('user.withdraw.history') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">Withdraw Records</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Transaction history</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                
                <a href="{{ route('user.transactions') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="{{ $iconBoxClass }} bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">Transaction</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">History</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <a href="{{ route('user.password.index') }}" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="{{ $iconBoxClass }} bg-pink-50 text-pink-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">Security</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Password</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <a href="#" class="{{ $itemClass }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="{{ $iconBoxClass }} bg-teal-50 text-teal-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-700 truncate">App</p>
                            <p class="text-[9px] text-slate-400 font-medium truncate">Download</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

            </div>

            <div class="pt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-50 text-red-600 font-black py-4 rounded-[1.5rem] border border-red-100 shadow-sm active:scale-95 transition-all flex items-center justify-center gap-2 hover:bg-red-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="text-xs uppercase tracking-widest">Sign Out</span>
                    </button>
                </form>
            </div>

            <p class="text-center text-[9px] text-slate-300 font-bold uppercase tracking-[0.3em] pb-4">Ver 3.1</p>
        </div>
    </div>
@endsection