@extends('backend.users.layouts.mobile')

@section('content')
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="min-h-screen bg-[#F8F9FA] pb-24 font-sans selection:bg-red-100" x-data="rechargeApp()">

        <div class="bg-slate-900 pt-6 pb-20 px-6 rounded-b-[2.5rem] shadow-xl relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-600/20 to-transparent rounded-full blur-[80px] -mr-20 -mt-20 pointer-events-none">
            </div>
            <div class="relative z-10 flex items-center justify-center">
                <h2 class="text-white text-xs font-black uppercase tracking-[0.2em]">Add Funds</h2>
            </div>
            <div class="mt-8 text-center relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 opacity-80">Current Wallet</p>
                <div class="flex items-center justify-center gap-1">
                    <span class="text-lg font-bold text-red-500 italic">₹</span>
                    <h1 class="text-3xl font-black text-white tracking-tighter tabular-nums truncate max-w-full px-2">
                        {{ number_format(Auth::user()->wallet->income_wallet + Auth::user()->wallet->personal_wallet, 2) }}
                    </h1>
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto px-4 -mt-12 relative z-20 space-y-5">
            <div class="mb-4">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-emerald-100/50">
                        <div
                            class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-tight">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error') || $errors->any())
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
                        class="bg-red-50 border border-red-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-red-100/50">
                        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">Transaction Failed</p>
                            <p class="text-[11px] font-bold text-red-600 leading-tight">
                                {{ session('error') ?? $errors->first() }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <form action="{{ route('user.recharge.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div
                    class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/40 border border-white relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-900"></div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Enter
                        Amount</label>

                    <div
                        class="relative flex items-center border-b border-slate-100 focus-within:border-red-500 transition-all pb-2">
                        <span class="text-2xl font-black text-slate-300 mr-2 italic">₹</span>
                        <input type="number" x-model="amount" name="amount" placeholder="{{ $min_recharge }}"
                            class="w-full text-3xl font-black text-slate-900 outline-none placeholder:text-slate-100 bg-transparent tabular-nums">
                    </div>

                    <div class="flex gap-2 mt-5 overflow-x-auto no-scrollbar pb-1">
                        <template x-for="val in [500, 1000, 2000, 5000, 10000]">
                            <button type="button" @click="amount = val"
                                class="px-4 py-2 rounded-xl text-[10px] font-black border transition-all active:scale-95 shrink-0"
                                :class="amount == val ? 'bg-slate-900 text-white border-slate-900 shadow-md' :
                                    'bg-slate-50 text-slate-500 border-transparent'">
                                +<span x-text="val"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-1 rounded-2xl flex shadow-sm border border-slate-100 mt-4 h-14">
                    <button type="button" @click="method = 'upi'"
                        class="flex-1 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2"
                        :class="method === 'upi' ? 'bg-red-600 text-white shadow-lg' : 'text-slate-400'">
                        UPI PAY
                    </button>
                    <button type="button" @click="method = 'usdt'"
                        class="flex-1 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2"
                        :class="method === 'usdt' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400'">
                        USDT
                    </button>
                </div>

                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 mt-4 min-h-[300px]">

                    <div x-show="method === 'upi'" x-cloak x-transition.opacity>
                        <div class="text-center">
                            <span
                                class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest mb-6 inline-block">Online
                                Gateway</span>

                            <div
                                class="w-48 h-48 mx-auto bg-white p-2 rounded-[2rem] border-2 border-dashed border-slate-200 shadow-inner mb-6 relative flex items-center justify-center overflow-hidden">
                                @if ($qr_code)
                                    <img src="{{ asset($qr_code) }}" class="w-full h-full object-cover rounded-[1.5rem]">
                                @else
                                    <div class="text-[9px] font-bold text-slate-300 uppercase">Wait for QR...</div>
                                @endif
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between border border-slate-100 group active:bg-slate-100 transition-colors"
                                @click="copyToClipboard('{{ $upi_id }}')">
                                <div class="text-left overflow-hidden w-full pr-2">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-0.5">Payment ID</p>
                                    <p class="text-xs font-black text-slate-800 truncate">{{ $upi_id }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1 mb-1.5 block">UTR
                                Number</label>
                            <input type="number" name="utr" placeholder="12 Digit Reference No."
                                class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-xs font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-red-500/20">
                        </div>
                    </div>

                    <div x-show="method === 'usdt'" x-cloak x-transition.opacity>
                        <div
                            class="bg-gradient-to-br from-emerald-600 to-teal-800 p-5 rounded-2xl text-center mb-6 shadow-lg text-white">
                            <p class="text-[9px] font-bold uppercase tracking-widest opacity-80 mb-1">Conversion Price</p>
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-3xl font-black tabular-nums" x-text="calculateUSDT()">0.00</span>
                                <span class="text-xs font-bold opacity-60">USDT</span>
                            </div>
                            <p class="text-[8px] font-black mt-2 opacity-50 uppercase tracking-tighter">Live Rate: 1 USDT =
                                ₹{{ $usdt_rate }}</p>
                        </div>

                        <div class="mb-5">
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1 mb-1 block">Network</label>
                            <select x-model="netIndex"
                                class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-xs font-black outline-none ring-1 ring-slate-100">
                                <template x-for="(net, index) in networks" :key="index">
                                    <option :value="index" x-text="net.network"></option>
                                </template>
                            </select>
                        </div>

                        <div class="text-center mb-6">
                            <div
                                class="w-44 h-44 mx-auto bg-white p-2 rounded-2xl border-2 border-dashed border-slate-200 mb-5 flex items-center justify-center overflow-hidden">
                                <img :src="'/' + getCurrentQR()" class="w-full h-full object-cover rounded-xl"
                                    x-show="getCurrentQR()">
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between border border-slate-100 active:bg-slate-100 transition-colors"
                                @click="copyToClipboard(getCurrentAddress())">
                                <div class="text-left overflow-hidden w-full pr-2">
                                    <p class="text-[8px] font-black text-slate-400 uppercase">Wallet Address</p>
                                    <p class="text-[11px] font-mono font-bold text-slate-800 truncate"
                                        x-text="getCurrentAddress()"></p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1 mb-1 block">Hash ID</label>
                            <input type="text" name="hash" placeholder="Enter TxID"
                                class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-xs font-bold outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50">
                        <div
                            class="relative w-full h-36 bg-slate-50 rounded-[1.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden active:bg-slate-100 transition-all">
                            <input type="file" name="screenshot" @change="handleFile"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" required>
                            <img x-show="preview" :src="preview"
                                class="absolute inset-0 w-full h-full object-cover z-20">
                            <div x-show="!preview" class="flex flex-col items-center z-10 text-slate-400">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-[10px] font-black uppercase tracking-widest">Attach Proof</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <input type="hidden" name="method" x-model="method">
                    <button type="submit"
                        class="w-full py-5 rounded-3xl font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl transition-all active:scale-95 text-white flex items-center justify-center gap-3"
                        :class="method === 'upi' ? 'bg-slate-900 shadow-slate-400' : 'bg-emerald-600 shadow-emerald-400'">
                        Process Deposit
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function rechargeApp() {
            return {
                amount: '',
                method: 'upi',
                networks: @json($usdt_methods),
                rate: {{ $usdt_rate }},
                netIndex: 0,
                preview: null,

                calculateUSDT() {
                    if (!this.amount || this.amount <= 0) return '0.00';
                    return (parseFloat(this.amount) / this.rate).toFixed(2);
                },
                getCurrentAddress() {
                    return this.networks[this.netIndex]?.address || '---';
                },
                getCurrentQR() {
                    return this.networks[this.netIndex]?.qr || '';
                },
                copyToClipboard(text) {
                    if (!text) return;
                    navigator.clipboard.writeText(text);
                    alert('Copied to clipboard!');
                },
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.preview = URL.createObjectURL(file);
                    }
                }
            }
        }
    </script>
@endsection
