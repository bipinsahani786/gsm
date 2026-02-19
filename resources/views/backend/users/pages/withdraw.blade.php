@extends('backend.users.layouts.mobile')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans" x-data="withdrawApp()">
    
    <div class="bg-slate-900 pt-8 pb-16 px-6 rounded-b-[3rem] shadow-xl text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-48 h-48 bg-red-600/10 rounded-full blur-3xl -ml-12 -mt-12"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">Request Payout</h2>
        
        <div class="mt-6 flex justify-center gap-4 relative z-10">
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-left min-w-[125px]">
                <p class="text-[8px] font-black text-slate-400 uppercase">Personal Wallet</p>
                <p class="text-sm font-black text-white">₹{{ number_format(Auth::user()->wallet->personal_wallet, 2) }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-left min-w-[125px]">
                <p class="text-[8px] font-black text-slate-400 uppercase">Income Wallet</p>
                <p class="text-sm font-black text-white">₹{{ number_format(Auth::user()->wallet->income_wallet, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-5 -mt-8 relative z-20 space-y-5">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-emerald-700 text-xs font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 p-4 rounded-2xl text-red-700 text-xs font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.withdraw.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50">
                <label class="text-[10px] font-black text-slate-400 uppercase mb-4 block tracking-widest text-center">Source Wallet</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="wallet = 'personal'" 
                        class="py-3 rounded-xl font-black text-[10px] uppercase tracking-widest border transition-all"
                        :class="wallet === 'personal' ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-slate-50 text-slate-400 border-transparent'">
                        Personal
                    </button>
                    <button type="button" @click="wallet = 'income'" 
                        class="py-3 rounded-xl font-black text-[10px] uppercase tracking-widest border transition-all"
                        :class="wallet === 'income' ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-slate-50 text-slate-400 border-transparent'">
                        Income
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 mt-4 shadow-xl border border-slate-50">
                <label class="text-[10px] font-black text-slate-400 uppercase mb-3 block">Withdrawal Amount</label>
                <div class="relative flex items-center border-b-2 border-slate-50 focus-within:border-red-500 transition-all pb-2">
                    <span class="text-2xl font-black text-slate-300 mr-2">₹</span>
                    <input type="number" x-model="amount" name="amount" :placeholder="'Min: ' + getMin()" 
                        class="w-full text-3xl font-black text-slate-900 outline-none bg-transparent tabular-nums" required>
                </div>

                <div class="mt-6 bg-slate-50 rounded-2xl p-4 space-y-2 border border-slate-100">
                    <div class="flex justify-between text-[10px] font-bold">
                        <span class="text-slate-400 uppercase">Commission ({{ $settings['withdraw_commission'] }}%)</span>
                        <span class="text-red-500">-₹<span x-text="calculateFee()">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-xs font-black pt-2 border-t border-slate-200">
                        <span class="text-slate-800 uppercase tracking-tighter">Settlement Amount</span>
                        <span class="text-emerald-600 font-mono italic">₹<span x-text="getFinalAmount()">0.00</span></span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6  mt-4 shadow-xl border border-slate-50">
                <label class="text-[10px] font-black text-slate-400 uppercase mb-4 block text-center tracking-widest">Payout Method</label>
                
                <div class="space-y-3">
                    <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl border-2 transition-all cursor-pointer"
                        :class="method === 'bank' ? 'border-slate-900 bg-white shadow-md' : 'border-transparent'">
                        <input type="radio" name="method" value="bank" x-model="method" class="hidden">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div class="ml-4 overflow-hidden">
                            <p class="text-[11px] font-black text-slate-800 uppercase leading-none">Bank Transfer</p>
                            @if($bank)
                                <p class="text-[9px] font-bold text-slate-400 mt-1 truncate">{{ $bank->bank_name }} ({{ substr($bank->account_number, -4) }})</p>
                            @else
                                <a href="{{ route('user.bind.index') }}" class="text-[9px] font-black text-red-500 uppercase mt-1 inline-block animate-pulse">! Bind Bank Details</a>
                            @endif
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 bg-slate-50 rounded-2xl border-2 transition-all cursor-pointer"
                        :class="method === 'crypto' ? 'border-emerald-600 bg-white shadow-md' : 'border-transparent'">
                        <input type="radio" name="method" value="crypto" x-model="method" class="hidden">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="ml-4 overflow-hidden">
                            <p class="text-[11px] font-black text-slate-800 uppercase leading-none">USDT (Crypto)</p>
                            @if($crypto)
                                <p class="text-[9px] font-bold text-slate-400 mt-1 truncate">{{ substr($crypto->wallet_address, 0, 10) }}...</p>
                            @else
                                <a href="{{ route('user.bind.index') }}" class="text-[9px] font-black text-red-500 uppercase mt-1 inline-block animate-pulse">! Bind Wallet Address</a>
                            @endif
                        </div>
                    </label>
                </div>
            </div>

            <input type="hidden" name="wallet_type" :value="wallet">
            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-5 rounded-[2.5rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl transition-all active:scale-95 text-white flex items-center justify-center gap-3 disabled:opacity-50"
                    :class="method === 'bank' ? 'bg-slate-900' : 'bg-emerald-600'"
                    :disabled="!amount || amount < getMin()">
                    Confirm Withdrawal
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>

            <p class="mt-4 text-xs text-slate-400 font-bold">Payouts are processed within 24 hours</p>

        </form>
    </div>
</div>

<script>
function withdrawApp() {
    return {
        amount: '',
        wallet: 'personal',
        method: 'bank',
        commission: {{ $settings['withdraw_commission'] ?? 0 }},
        minPersonal: {{ $settings['min_withdraw_personal'] ?? 0 }},
        minIncome: {{ $settings['min_withdraw_income'] ?? 0 }},

        getMin() {
            return this.wallet === 'personal' ? this.minPersonal : this.minIncome;
        },
        calculateFee() {
            if(!this.amount || isNaN(this.amount)) return '0.00';
            return (parseFloat(this.amount) * (this.commission / 100)).toFixed(2);
        },
        getFinalAmount() {
            if(!this.amount || isNaN(this.amount)) return '0.00';
            let final = parseFloat(this.amount) - parseFloat(this.calculateFee());
            return final > 0 ? final.toFixed(2) : '0.00';
        }
    }
}
</script>
@endsection