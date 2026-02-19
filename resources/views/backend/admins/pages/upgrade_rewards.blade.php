@extends('backend.admins.layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Upgrade Promotions</h1>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Set time-bound rewards for level upgrades
            </p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 mb-8">
            <form action="{{ route('admin.rewards.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">From Level</label>
                        <select name="from_level_id"
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none">
                            <option value="">Any Level / New User</option>
                            @foreach ($levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">To Level</label>
                        <select name="to_level_id" required
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500/20 appearance-none border-2 border-emerald-100">
                            <option value="">Select Target Level</option>
                            @foreach ($levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Bonus Reward
                            (₹)</label>
                        <input type="number" step="0.01" name="reward_amount" required placeholder="Amount"
                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-black text-orange-600 outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>

                    <div class="md:col-span-2 grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">Start Date &
                                Time</label>
                            <input type="datetime-local" name="start_date" required
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-1 mb-2 block">End Date &
                                Time</label>
                            <input type="datetime-local" name="end_date" required
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">
                        Create Promotion
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Upgrade Path</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Bonus Reward</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Validity Period</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Status</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($rewards as $reward)
                        @php
                            // Check if current date is between start and end
                            $isActive = \Carbon\Carbon::now()->between($reward->start_date, $reward->end_date);
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-5">
                                <div class="flex items-center gap-2">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[10px] font-bold">
                                        {{ $reward->from_level_id ? 'Level ' . $reward->from_level_id : 'Any/New' }}
                                    </span>
                                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-[10px] font-black">
                                        Level {{ $reward->to_level_id }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-5 font-black text-orange-600">₹{{ number_format($reward->reward_amount, 2) }}</td>
                            <td class="p-5">
                                <p class="text-[10px] font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($reward->start_date)->format('d M, h:i A') }}</p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1">To:
                                    {{ \Carbon\Carbon::parse($reward->end_date)->format('d M, h:i A') }}</p>
                            </td>
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.rewards.status', $reward->id) }}"
                                        class="relative inline-flex items-center cursor-pointer transition-all active:scale-95">
                                        <div
                                            class="w-10 h-5 {{ $reward->status ? 'bg-emerald-500' : 'bg-slate-300' }} rounded-full shadow-inner transition-colors duration-300">
                                        </div>
                                        <div
                                            class="absolute w-6 h-6 bg-white rounded-full shadow-md border border-slate-100 transform transition-transform duration-300 flex items-center justify-center {{ $reward->status ? 'translate-x-5' : '-translate-x-1' }}">
                                            @if ($reward->status)
                                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </a>

                                    @if ($isActive)
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Running</span>
                                    @else
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest text-red-500 bg-red-50 px-2 py-0.5 rounded">Expired</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-5 text-center">
                                <a href="{{ route('admin.rewards.delete', $reward->id) }}"
                                    onclick="return confirm('Delete this promotion?')"
                                    class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
