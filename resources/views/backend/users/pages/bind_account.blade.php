@extends('backend.users.layouts.mobile')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans" x-data="{ tab: 'bank' }">
    
    
    <div class="bg-slate-900 pt-8 pb-16 px-6 rounded-b-[2.5rem] shadow-xl text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl -mr-12 -mt-12"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">Withdrawal Methods</h2>
        <p class="text-slate-400 text-[10px] font-bold uppercase mt-1 relative z-10">Bind your accounts for fast payouts</p>
    </div>

    <div class="max-w-md mx-auto px-5 -mt-10 relative z-20 space-y-6">
        <div class="px-6 mt-4">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-emerald-100/50 transition-all">
            <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-xs font-black text-emerald-700 uppercase tracking-tight">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" 
             class="bg-red-50 border border-red-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-red-100/50 transition-all">
            <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div class="flex flex-col text-left">
                <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">Action Denied</p>
                <p class="text-[11px] font-bold text-red-600 leading-tight">
                    {{ session('error') ?? $errors->first() }}
                </p>
            </div>
        </div>
    @endif
</div>
        <div class="bg-white p-1.5 rounded-[1.5rem] flex shadow-sm border border-slate-100 h-14">
            <button type="button" @click="tab = 'bank'" 
                class="flex-1 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2"
                :class="tab === 'bank' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400'">
                Bank Account
            </button>
            <button type="button" @click="tab = 'crypto'" 
                class="flex-1 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2"
                :class="tab === 'crypto' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400'">
                USDT Wallet
            </button>
        </div>

        <div x-show="tab === 'bank'" x-cloak x-transition.opacity>
            <form action="{{ route('user.bind.save') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="bank">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 space-y-4">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">Account Holder Name</label>
                        <input type="text" name="account_holder" value="{{ $bank->account_holder ?? '' }}" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-slate-900/10">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ $bank->bank_name ?? '' }}" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-slate-900/10">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">Account Number</label>
                        <input type="number" name="account_number" value="{{ $bank->account_number ?? '' }}" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-slate-900/10">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">IFSC Code</label>
                        <input type="text" name="ifsc_code" value="{{ $bank->ifsc_code ?? '' }}" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-slate-900/10">
                    </div>
                </div>
                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">Save Bank Details</button>
            </form>
        </div>

        <div x-show="tab === 'crypto'" x-cloak x-transition.opacity>
            <form action="{{ route('user.bind.save') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="crypto">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 space-y-4">
                    <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-emerald-600 uppercase">USDT Network</p>
                            <p class="text-xs font-bold text-emerald-800">Support: TRC20 / BEP20</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">Select Network</label>
                        <select name="network" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none ring-1 ring-slate-100 appearance-none">
                            <option value="TRC20" {{ ($crypto->network ?? '') == 'TRC20' ? 'selected' : '' }}>USDT (TRC20)</option>
                            <option value="BEP20" {{ ($crypto->network ?? '') == 'BEP20' ? 'selected' : '' }}>USDT (BEP20)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1.5 block tracking-widest">Wallet Address</label>
                        <input type="text" name="wallet_address" value="{{ $crypto->wallet_address ?? '' }}" placeholder="Paste your address here" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                </div>
                <button type="submit" class="w-full py-5 bg-emerald-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">Save Crypto Wallet</button>
            </form>
        </div>

    </div>
</div>
@endsection