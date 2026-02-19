@extends('backend.admins.layouts.app')

@section('content')
<div class="p-6">
     <div class="px-4 mt-2 mb-2">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
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
                    class="bg-red-50 border border-red-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
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
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Withdrawal Requests</h1>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Process user payouts and verify details</p>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400">User & Wallet</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400">Payout Details</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400">Settlement</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400">Status</th>
                    <th class="p-5 text-[10px] font-black uppercase text-slate-400 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($withdrawals as $w)
                @php $acc = json_decode($w->account_details); @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-5">
                        <p class="font-bold text-slate-800 text-sm">{{ $w->user->name }}</p>
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-500">{{ $w->wallet_type }}</span>
                    </td>
                    <td class="p-5">
                        <div class="text-[11px] font-bold text-slate-600">
                            @if($w->method == 'bank')
                                <p>Bank: {{ $acc->bank_name }}</p>
                                <p class="text-blue-600 font-mono select-all">A/C: {{ $acc->account_number }}</p>
                            @else
                                <p>Crypto: {{ $acc->network }}</p>
                                <p class="text-emerald-600 font-mono select-all text-[9px]">{{ $acc->wallet_address }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-black text-slate-900 text-sm">₹{{ number_format($w->final_amount, 2) }}</p>
                        <p class="text-[9px] text-slate-400 font-bold">Fee: ₹{{ $w->fee }}</p>
                    </td>
                    <td class="p-5">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border
                            {{ $w->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($w->status == 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100') }}">
                            {{ $w->status }}
                        </span>
                    </td>
                    <td class="p-5 text-center">
                        @if($w->status == 'pending')
                        <div class="flex gap-2 justify-center">
                            <button onclick="openApproveModal({{ $w->id }}, {{ $w->final_amount }})" class="p-2 bg-emerald-500 text-white rounded-xl shadow-lg active:scale-90 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            <button onclick="openRejectModal({{ $w->id }})" class="p-2 bg-red-500 text-white rounded-xl shadow-lg active:scale-90 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @else
                        <p class="text-[9px] font-bold text-slate-300 italic">{{ $w->admin_remark }}</p>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="approveModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl">
        <h3 class="text-xl font-black text-slate-800 mb-2 uppercase">Complete Payout</h3>
        <p class="text-xs text-slate-400 font-bold mb-6">Enter Transaction/Reference ID after sending ₹<span id="payoutAmount"></span></p>
        <form id="approveForm" method="POST">
            @csrf
            <input type="text" name="transaction_id" required placeholder="Paste UTR / TxID here" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-emerald-500/20 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModals()" class="py-4 rounded-2xl font-black text-xs uppercase text-slate-400 bg-slate-50">Cancel</button>
                <button type="submit" class="py-4 rounded-2xl font-black text-xs uppercase text-white bg-emerald-600 shadow-lg">Approve</button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl">
        <h3 class="text-xl font-black text-slate-800 mb-2 uppercase">Reject Payout</h3>
        <p class="text-xs text-slate-400 font-bold mb-6">User's balance will be automatically refunded.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="admin_remark" required placeholder="Reason for rejection..." class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-red-500/20 mb-6 min-h-[100px]"></textarea>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModals()" class="py-4 rounded-2xl font-black text-xs uppercase text-slate-400 bg-slate-50">Cancel</button>
                <button type="submit" class="py-4 rounded-2xl font-black text-xs uppercase text-white bg-red-600 shadow-lg">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(id, amount) {
        document.getElementById('approveForm').action = "/admin/withdrawals/" + id + "/approve";
        document.getElementById('payoutAmount').innerText = amount;
        document.getElementById('approveModal').classList.remove('hidden');
    }
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = "/admin/withdrawals/" + id + "/reject";
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeModals() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection