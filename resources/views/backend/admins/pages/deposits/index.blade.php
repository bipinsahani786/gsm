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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Deposit Requests</h1>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Manage User Recharge Payments</p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">User Details</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Amount & Method</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Proof Details</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400">Status</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($deposits as $dep)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-5">
                                <p class="font-bold text-slate-800 text-sm">{{ $dep->user->name }}</p>
                                <p class="text-[10px] font-mono text-slate-400 uppercase">UID: {{ $dep->user->uid }}</p>
                            </td>
                            <td class="p-5">
                                <p class="font-black text-slate-900 text-base">₹{{ number_format($dep->amount, 2) }}</p>
                                <span
                                    class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $dep->method == 'upi' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                                    {{ $dep->method }}
                                </span>
                            </td>
                            <td class="p-5 text-[11px] font-mono text-slate-600">
                                <p class="font-bold">Ref: {{ $dep->utr_hash ?? 'N/A' }}</p>
                                <button onclick="showImage('{{ asset($dep->screenshot) }}')"
                                    class="mt-1 text-blue-500 font-bold uppercase text-[9px] hover:underline">View
                                    Screenshot</button>
                            </td>
                            <td class="p-5">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase border
                            {{ $dep->status == 'pending' ? 'bg-orange-50 text-orange-600 border-orange-100' : ($dep->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100') }}">
                                    {{ $dep->status }}
                                </span>
                            </td>
                            <td class="p-5 text-center">
                                @if ($dep->status == 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST"
                                            onsubmit="return confirm('Add balance to user wallet?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl shadow-md active:scale-90 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $dep->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-xl shadow-md active:scale-90 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <p class="text-[10px] font-bold text-slate-300 italic">
                                        {{ $dep->remarks ?? 'Processed' }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-5 border-t border-slate-50">
                {{ $deposits->links() }}
            </div>
        </div>
    </div>

    <div id="rejectModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl animate-in zoom-in duration-300">
            <h3 class="text-xl font-black text-slate-800 mb-2 uppercase italic">Reject Deposit</h3>
            <p class="text-xs text-slate-400 font-bold mb-6">Explain why this payment is being rejected.</p>

            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="remarks" required placeholder="Reason (e.g. Invalid UTR, Fake Screenshot)"
                    class="w-full bg-slate-50 border-none rounded-2xl p-5 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-red-500/20 mb-6 min-h-[120px]"></textarea>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeRejectModal()"
                        class="py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 bg-slate-50 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit"
                        class="py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-white bg-red-600 shadow-lg shadow-red-200 active:scale-95 transition-all">Confirm
                        Reject</button>
                </div>
            </form>
        </div>
    </div>

    <div id="imageModal" onclick="this.classList.add('hidden')"
        class="hidden fixed inset-0 bg-slate-900/90 z-[110] flex items-center justify-center p-6 cursor-zoom-out">
        <img id="previewImg" src="" class="max-w-full max-h-full rounded-2xl shadow-2xl">
    </div>

    <script>
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = "/admin/deposits/" + id + "/reject";
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function showImage(src) {
            document.getElementById('previewImg').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }
    </script>
@endsection
