@extends('backend.admins.layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto pb-20">

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase">VIP Levels</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Manage Task Earnings</p>
            </div>
            <div class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">
                Total Levels: {{ $levels->count() }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 sticky top-6 shadow-xl shadow-slate-200/50"
                    x-data="levelForm()">

                    <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create New Level
                    </h3>

                    <form action="{{ route('admin.levels.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf

                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Level Name</label>
                            <input type="text" name="name" placeholder="e.g. VIP 1"
                                class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                        </div>

                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Unlock Amount
                                (₹)</label>
                            <input type="number" name="min_deposit" placeholder="1000"
                                class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Daily
                                    Tasks</label>
                                <input type="number" name="daily_limit" x-model="tasks" placeholder="10"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Per Order
                                    (₹)</label>
                                <input type="number" step="0.01" name="rate" x-model="rate" placeholder="5"
                                    class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                            </div>
                        </div>

                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Level Icon</label>
                            <input type="file" name="icon"
                                class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-red-50 file:text-red-600 hover:file:bg-red-100">
                        </div>

                        <div class="bg-slate-900 p-5 rounded-2xl text-center mt-4">
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Estimated Daily Income
                            </p>
                            <p class="text-2xl font-black text-white mt-1">
                                ₹ <span x-text="(tasks * rate).toFixed(2)">0.00</span>
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-red-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-200 active:scale-95 transition-all">
                            Save Level
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($levels as $level)
                        <div
                            class="bg-white p-6 rounded-[2.5rem] border border-slate-100 hover:border-red-100 transition-all group relative overflow-hidden">

                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center overflow-hidden">
                                        @if ($level->icon)
                                            <img src="{{ asset($level->icon) }}">
                                        @else
                                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-black text-lg text-slate-900">{{ $level->name }}</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Unlock:
                                            ₹{{ number_format($level->min_deposit) }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('admin.levels.destroy', $level->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this level?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-2">
                                <div class="bg-slate-50 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] text-slate-400 font-black uppercase">Daily Orders</p>
                                    <p class="text-xl font-black text-slate-800">{{ $level->daily_limit }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-2xl text-center">
                                    <p class="text-[9px] text-slate-400 font-black uppercase">Per Order</p>
                                    <p class="text-xl font-black text-green-600">₹{{ $level->rate }}</p>
                                </div>
                            </div>

                            <div class="bg-slate-900 p-3 rounded-2xl text-center mt-2">
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Daily Profit</p>
                                <p class="text-lg font-black text-white">
                                    ₹{{ number_format($level->daily_limit * $level->rate, 2) }}</p>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        function levelForm() {
            return {
                tasks: '',
                rate: ''
            }
        }
    </script>
@endsection
