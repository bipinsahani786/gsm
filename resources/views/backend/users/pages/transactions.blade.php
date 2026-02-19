@extends('backend.users.layouts.mobile')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-32">
    <div class="bg-slate-900 pt-8 pb-12 px-6 rounded-b-[2.5rem] shadow-xl relative overflow-hidden text-center">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">Transaction Logs</h2>
        <p class="text-slate-400 text-[10px] font-bold uppercase mt-1 relative z-10">Your complete financial history</p>
    </div>

    <div class="px-5 -mt-6 space-y-3 relative z-20">
        @forelse($transactions as $trx)
        <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 flex items-center justify-between transition-active active:scale-[0.98]">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 
                    {{ in_array($trx->type, ['deposit', 'commission', 'referral']) ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                    @if(in_array($trx->type, ['deposit', 'commission', 'referral']))
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="text-[11px] font-black text-slate-800 uppercase leading-tight truncate">
                        {{ str_replace('_', ' ', $trx->type) }}
                    </p>
                    <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-tighter truncate">
                        {{ $trx->details }}
                    </p>
                    <p class="text-[8px] text-slate-300 font-medium mt-0.5 italic">
                        {{ $trx->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>

            <div class="text-right shrink-0">
                <p class="text-sm font-black {{ in_array($trx->type, ['deposit', 'commission', 'referral']) ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ in_array($trx->type, ['deposit', 'commission', 'referral']) ? '+' : '-' }}₹{{ number_format($trx->amount, 2) }}
                </p>
                <p class="text-[8px] font-bold text-slate-300 uppercase tracking-widest mt-1">
                    Bal: ₹{{ number_format($trx->post_balance, 2) }}
                </p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] p-12 text-center border border-slate-100 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No Transactions Found</p>
        </div>
        @endforelse

        <div class="mt-4 px-2">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection