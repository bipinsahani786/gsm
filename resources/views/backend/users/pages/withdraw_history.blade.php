@extends('backend.users.layouts.mobile')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-32">
    <div class="bg-slate-900 pt-8 pb-12 px-6 rounded-b-[2.5rem] shadow-xl">
        <h2 class="text-white text-sm font-black uppercase tracking-widest text-center">Withdrawal History</h2>
    </div>

    <div class="px-5 -mt-6 space-y-4">
        @foreach($history as $row)
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl {{ $row->status == 'approved' ? 'bg-emerald-50 text-emerald-600' : ($row->status == 'rejected' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600') }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-800 uppercase leading-none">₹{{ number_format($row->amount, 2) }}</p>
                    <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase">{{ $row->created_at->format('d M, h:i A') }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-tighter
                    {{ $row->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : ($row->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') }}">
                    {{ $row->status }}
                </span>
                <p class="text-[9px] font-bold text-slate-400 mt-2 italic">{{ strtoupper($row->method) }}</p>
            </div>
        </div>
        @endforeach

        @if($history->isEmpty())
            <div class="text-center py-20">
                <p class="text-xs font-bold text-slate-400">No records found yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection