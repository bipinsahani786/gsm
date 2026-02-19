@extends('backend.admins.layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Withdrawal Settings</h1>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Configure limits and commissions for
                payouts</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-xs font-black text-emerald-700 uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        <div class="max-w-4xl">
            <form action="{{ route('admin.settings.withdrawal.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                <i class="w-5 h-5" data-lucide="wallet"></i>
                            </div>
                            <h3 class="font-black text-slate-800 uppercase text-sm tracking-wider">Personal Wallet</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Minimum
                                    Withdrawal (₹)</label>
                                <input type="number" name="min_withdraw_personal"
                                    value="{{ $settings['min_withdraw_personal'] ?? 500 }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="w-5 h-5" data-lucide="trending-up"></i>
                            </div>
                            <h3 class="font-black text-slate-800 uppercase text-sm tracking-wider">Income Wallet</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Minimum
                                    Withdrawal (₹)</label>
                                <input type="number" name="min_withdraw_income"
                                    value="{{ $settings['min_withdraw_income'] ?? 1000 }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 md:col-span-2 mt-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-black text-slate-800 uppercase text-sm tracking-wider">Safety & Max Limits</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Max
                                    Withdrawal (Per Transaction)</label>
                                <input type="number" name="max_withdraw_limit"
                                    value="{{ $settings['max_withdraw_limit'] ?? 50000 }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                                <p class="text-[9px] text-slate-400 mt-2 font-bold italic">* Ek baar mein user isse zyada
                                    withdraw nahi kar payega.</p>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Daily Max
                                    Withdrawal (Total)</label>
                                <input type="number" name="max_withdraw_daily"
                                    value="{{ $settings['max_withdraw_daily'] ?? 100000 }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                                <p class="text-[9px] text-slate-400 mt-2 font-bold italic">* Pure din mein user ki total
                                    withdrawal limit.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 md:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                                <i class="w-5 h-5" data-lucide="percent"></i>
                            </div>
                            <h3 class="font-black text-slate-800 uppercase text-sm tracking-wider">Withdrawal Fees &
                                Commission</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Withdrawal
                                    Commission (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="withdraw_commission"
                                        value="{{ $settings['withdraw_commission'] ?? 10 }}"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-orange-500/20">
                                    <span
                                        class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-slate-300">%</span>
                                </div>
                                <p class="text-[9px] text-slate-400 mt-2 font-bold italic">* This amount will be deducted
                                    from user's withdrawal request.</p>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Max Daily
                                    Withdrawals</label>
                                <input type="number" name="daily_withdraw_limit"
                                    value="{{ $settings['daily_withdraw_limit'] ?? 1 }}"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-orange-500/20">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 active:scale-95 transition-all">
                        Save Global Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
