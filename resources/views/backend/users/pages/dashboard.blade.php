@extends('backend.users.layouts.mobile')

@section('content')
    <div class="bg-slate-900 rounded-b-[2.5rem] px-5 pt-2 pb-14 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="px-5 mt-4 mb-4">
            @if (session('success'))
                <div
                    class="bg-emerald-50 border border-emerald-100 p-3 rounded-2xl text-[10px] font-black text-emerald-700 uppercase tracking-widest text-center shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-50 border border-red-100 p-3 rounded-2xl text-[10px] font-black text-red-700 uppercase tracking-widest text-center shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-[2rem] p-6 shadow-xl relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Assets</p>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                        ₹{{ number_format($totalAssets, 2) }}
                    </h2>
                </div>
                <div class="text-right flex flex-col items-end">
                    <span
                        class="bg-slate-900 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-slate-200 uppercase tracking-widest">
                        {{ $positionName }}
                    </span>
                    <p class="text-[8px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Current Position</p>
                </div>
            </div>

            <div class="h-[1px] bg-gray-100 w-full my-4"></div>

            <div class="flex justify-between">
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Income Wallet</p>
                    <p class="text-sm font-black text-slate-800">₹{{ number_format($wallet->income_wallet ?? 0, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Personal Wallet</p>
                    <p class="text-sm font-black text-slate-800">₹{{ number_format($wallet->personal_wallet ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-5 -mt-8 relative z-20">
        <a href="{{ route('user.recharge') }}"
            class="block w-full bg-red-600 text-white text-center py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-red-200 active:scale-95 transition-all">
            Top Up Balance
        </a>
    </div>

    <section class="mt-8 px-5">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Statistics Overview</h3>
        
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Today Earning</p>
                <p class="text-[15px] font-black text-emerald-600 mt-1">₹{{ number_format($todayEarning, 2) }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Yesterday Earning</p>
                <p class="text-[15px] font-black text-slate-700 mt-1">₹{{ number_format($yesterdayEarning, 2) }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">This Week</p>
                <p class="text-[15px] font-black text-blue-600 mt-1">₹{{ number_format($thisWeekEarning, 2) }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">This Month</p>
                <p class="text-[15px] font-black text-purple-600 mt-1">₹{{ number_format($thisMonthEarning, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-center">
                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-tight">Team Task</p>
                <p class="text-xs font-black text-amber-500 mt-1.5">₹{{ number_format($teamTaskCommission, 2) }}</p>
            </div>
            
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-center">
                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-tight">Recommended</p>
                <p class="text-xs font-black text-pink-500 mt-1.5">₹{{ number_format($recommendedIncome, 2) }}</p>
            </div>
            
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-center">
                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-tight">Total Revenue</p>
                <p class="text-xs font-black text-slate-900 mt-1.5">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
    </section>

    <section class="mt-10 px-5 pb-10">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Investment Levels</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Join plans to earn daily</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @foreach ($levels as $level)
                <div
                    class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 flex items-center justify-between relative overflow-hidden transition-all active:scale-[0.98]">

                    @if (($user->level_id ?? 0) == $level->id)
                        <div
                            class="absolute top-0 right-0 bg-emerald-500 text-white text-[8px] font-black px-3 py-1 rounded-bl-xl uppercase tracking-widest">
                            Current</div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 shadow-inner shrink-0 p-2">
                            @if ($level->icon)
                                <img src="{{ asset($level->icon) }}" alt="{{ $level->name }}"
                                    class="w-full h-full object-contain drop-shadow-sm">
                            @else
                                <span class="text-lg font-black text-slate-800">{{ substr($level->name, 0, 2) }}</span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <h4 class="font-black text-slate-800 text-sm uppercase tracking-tight">{{ $level->name }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-black tracking-tighter">Rate:
                                    ₹{{ number_format($level->rate, 2) }}</span>
                            </div>
                            <p class="text-[9px] text-slate-400 font-bold mt-1.5 uppercase tracking-tighter">
                                Req. Deposit: <span
                                    class="text-slate-600">₹{{ number_format($level->min_deposit, 2) }}</span>
                            </p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">
                                Daily Limit: <span class="text-slate-600">{{ $level->daily_limit }} Tasks</span>
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0 ml-2">
                        @if (($user->level_id ?? 0) == $level->id)
                            <button disabled
                                class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest">
                                Active
                            </button>
                        @else
                            <form action="{{ route('user.level.join', $level->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to join {{ $level->name }} for ₹{{ number_format($level->min_deposit, 2) }}?')"
                                    class="bg-slate-900 text-white px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                                    Join
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection