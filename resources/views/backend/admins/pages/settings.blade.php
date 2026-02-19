@use('App\Models\Configuration')
@extends('backend.admins.layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto pb-24" x-data="paymentSettings()">

        <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                <h1 class="text-xl font-black text-slate-900 uppercase">Payment Settings</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Global Configuration</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 space-y-6">

                    <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em]">Basic Info</h3>

                    <input type="text" name="upi_id" value="{{ Configuration::get('upi_id') }}" placeholder="UPI ID"
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-red-500/20">

                    <input type="number" name="min_recharge" value="{{ Configuration::get('min_recharge') }}"
                        placeholder="Minimum Recharge ₹"
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-red-500/20">

                    <input type="number" step="0.01" name="usdt_rate" value="{{ Configuration::get('usdt_rate', 92) }}"
                        placeholder="₹ → USDT Rate"
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-black text-red-600 outline-none ring-1 ring-slate-100">

                </div>

                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 text-center">

                    <h3 class="font-black text-[10px] text-slate-400 uppercase tracking-widest mb-4">UPI QR Code</h3>

                    <div
                        class="relative mx-auto w-40 h-40 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">

                        <img x-show="upiPreview" :src="upiPreview" class="w-full h-full object-cover">

                        <template x-if="!upiPreview">
                            @if (Configuration::get('qr_code'))
                                <img src="{{ asset(Configuration::get('qr_code')) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-slate-400">Upload QR</span>
                            @endif
                        </template>

                        <input type="file" name="qr_code" @change="previewUPI($event)"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>

                </div>

            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden">

                <div class="p-6 md:p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <h3 class="font-black text-xs text-slate-900 uppercase tracking-widest">USDT Networks</h3>

                    <button type="button" @click="addNetwork()"
                        class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold">
                        Add Network
                    </button>

                </div>

                <div class="p-4 md:p-8 space-y-4">

                    <template x-for="(item,index) in usdt" :key="index">

                        <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100">

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

                                <div class="md:col-span-2 flex flex-col items-center gap-2">

                                    <div
                                        class="w-20 h-20 bg-white rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative">

                                        <img x-show="item.preview" :src="item.preview"
                                            class="w-full h-full object-cover">

                                        <template x-if="!item.preview">
                                            <img :src="'/' + item.qr" x-show="item.qr" class="w-full h-full object-cover">
                                        </template>

                                        <input type="file" :name="'usdt_qrs[' + index + ']'"
                                            @change="previewNetwork($event,index)"
                                            class="absolute inset-0 opacity-0 cursor-pointer">
                                    </div>

                                    <span class="text-[8px] font-black uppercase text-slate-400">Network QR</span>
                                </div>

                                <div class="md:col-span-3">
                                    <input type="text" name="networks[]" x-model="item.network"
                                        placeholder="Network (TRC20, ERC20)"
                                        class="w-full bg-white border-none rounded-xl py-3 px-5 text-sm outline-none ring-1 ring-slate-200 focus:ring-red-500">
                                </div>

                                <div class="md:col-span-6">
                                    <input type="text" name="addresses[]" x-model="item.address"
                                        placeholder="Wallet Address"
                                        class="w-full bg-white border-none rounded-xl py-3 px-5 text-sm outline-none ring-1 ring-slate-200 focus:ring-red-500">
                                </div>

                                <div class="md:col-span-1 text-right">
                                    <button type="button" @click="remove(index)"
                                        class="p-3 text-red-500 hover:bg-red-50 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </button>
                                </div>

                            </div>

                        </div>

                    </template>

                </div>

            </div>

            <div class="mt-8 bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-xs text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Referral Commission Levels
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">Set percentage rewards for 3 levels up.</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                        <div class="hidden md:block absolute top-1/2 left-10 right-10 h-1 bg-slate-100 -z-10 -translate-y-1/2 rounded-full"></div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-blue-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">1</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 1 (Direct)</h4>
                            <div class="relative">
                                <input type="number" step="0.1" name="referral_l1" value="{{ Configuration::get('referral_l1', 10) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">Earned by direct sponsor</p>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-blue-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">2</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 2</h4>
                            <div class="relative">
                                <input type="number" step="0.1" name="referral_l2" value="{{ Configuration::get('referral_l2', 5) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">Earned by upline's upline</p>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-blue-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">3</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 3</h4>
                            <div class="relative">
                                <input type="number" step="0.1" name="referral_l3" value="{{ Configuration::get('referral_l3', 2) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-blue-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">Top level commission</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-xs text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Task Commission Levels
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold mt-1">Set percentage rewards generated when a downline completes tasks.</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                        <div class="hidden md:block absolute top-1/2 left-10 right-10 h-1 bg-slate-100 -z-10 -translate-y-1/2 rounded-full"></div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-emerald-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">1</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 1 (Direct)</h4>
                            <div class="relative">
                                <input type="number" step="0.01" name="task_commission_l1" value="{{ Configuration::get('task_commission_l1', 5) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-emerald-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">From direct team tasks</p>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-emerald-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">2</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 2</h4>
                            <div class="relative">
                                <input type="number" step="0.01" name="task_commission_l2" value="{{ Configuration::get('task_commission_l2', 2) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-emerald-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">From L2 team tasks</p>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center relative group hover:border-emerald-200 transition-colors">
                            <div class="w-10 h-10 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 font-black text-xs border-4 border-white shadow-sm">3</div>
                            <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Level 3</h4>
                            <div class="relative">
                                <input type="number" step="0.01" name="task_commission_l3" value="{{ Configuration::get('task_commission_l3', 1) }}"
                                    class="w-full bg-slate-50 border-none rounded-xl py-3 px-4 text-center font-black text-lg outline-none ring-1 ring-slate-100 focus:ring-2 focus:ring-emerald-500/20">
                                <span class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-slate-300">%</span>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 font-medium">From L3 team tasks</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 text-right">
                <button type="submit"
                    class="bg-red-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-200 active:scale-95 transition-all">
                    Save Settings
                </button>
            </div>

        </form>
    </div>

    <script>
        lucide.createIcons();

        function paymentSettings() {
            return {

                upiPreview: null,

                usdt: {!! Configuration::get('usdt_methods', '[]') !!}.map(x => ({
                    ...x,
                    preview: null
                })),

                previewUPI(e) {
                    this.upiPreview = URL.createObjectURL(e.target.files[0]);
                },

                previewNetwork(e, index) {
                    this.usdt[index].preview = URL.createObjectURL(e.target.files[0]);
                },

                addNetwork() {
                    this.usdt.push({
                        network: '',
                        address: '',
                        qr: '',
                        preview: null
                    })
                },

                remove(i) {
                    this.usdt.splice(i, 1)
                }
            }
        }
    </script>
@endsection