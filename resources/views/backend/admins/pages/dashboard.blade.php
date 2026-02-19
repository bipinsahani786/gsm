@extends('backend.admins.layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Analytics Dashboard</h1>
        <p class="text-slate-500 text-sm italic">Showing real-time data for SP platform.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['title' => 'Total Users', 'value' => '1,240', 'color' => 'blue', 'icon' => 'users'],
                ['title' => 'Revenue', 'value' => '$45,200', 'color' => 'green', 'icon' => 'cash'],
                ['title' => 'Pending Payout', 'value' => '$1,840', 'color' => 'red', 'icon' => 'clock'],
                ['title' => 'Active Now', 'value' => '342', 'color' => 'orange', 'icon' => 'pulse']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $card['title'] }}</p>
            <h3 class="text-2xl font-black text-slate-900">{{ $card['value'] }}</h3>
            <div class="mt-4 w-full h-1 bg-slate-50 rounded-full overflow-hidden">
                <div class="bg-{{ $card['color'] }}-500 h-full w-2/3"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-900">Recent Transactions</h3>
            <button class="bg-slate-900 text-white text-[10px] px-4 py-2 rounded-xl font-bold uppercase tracking-wider">Export</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">User ID</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @for($i=1; $i<=5; $i++)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 text-sm font-bold text-slate-700">#ZY-202{{ $i }}</td>
                        <td class="px-8 py-5 text-sm font-black text-slate-900">$500.00</td>
                        <td class="px-8 py-5">
                            <span class="bg-green-50 text-green-600 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase italic">Completed</span>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-400">17 Feb, 2026</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection