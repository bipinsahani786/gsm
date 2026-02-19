@extends('backend.users.layouts.mobile')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans">
    
    <div class="bg-slate-900 pt-6 pb-12 px-6 rounded-b-[2.5rem] shadow-xl text-center">
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em]">Recharge History</h2>
    </div>

    <div class="px-5 -mt-6 space-y-4">
        @forelse($deposits as $record)
        <div class="bg-white p-5 rounded-[2.2rem] shadow-sm border border-slate-100 flex flex-col gap-4 relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-1.5 h-full 
                {{ $record->status == 'approved' ? 'bg-emerald-500' : ($record->status == 'rejected' ? 'bg-red-500' : 'bg-orange-400') }}">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center 
                        {{ $record->status == 'approved' ? 'bg-emerald-50 text-emerald-600' : ($record->status == 'rejected' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $record->method }} Deposit</p>
                        <p class="text-sm font-black text-slate-800 tabular-nums">₹{{ number_format($record->amount, 2) }}</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full border
                        {{ $record->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($record->status == 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100') }}">
                        {{ $record->status }}
                    </span>
                    <p class="text-[9px] font-bold text-slate-300 mt-1.5">{{ $record->created_at->format('d M, Y') }}</p>
                </div>
            </div>

            @if($record->status == 'rejected' && $record->remarks)
            <div class="bg-red-50 rounded-2xl p-4 border border-red-100/50 flex items-start gap-3">
                <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="min-w-0">
                    <p class="text-[9px] font-black text-red-400 uppercase tracking-tighter mb-0.5">Rejection Reason</p>
                    <p class="text-[11px] font-bold text-red-700 leading-snug">{{ $record->remarks }}</p>
                </div>
            </div>
            @endif

            <div class="flex justify-between border-t border-slate-50 pt-3">
                <p class="text-[9px] font-bold text-slate-300 uppercase">Ref ID</p>
                <p class="text-[10px] font-mono font-bold text-slate-500 truncate pl-4">{{ $record->utr_hash ?? '---' }}</p>
            </div>
        </div>
        @empty
        <div class="py-20 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <p class="text-slate-400 font-black uppercase text-xs tracking-widest">No Records Found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection