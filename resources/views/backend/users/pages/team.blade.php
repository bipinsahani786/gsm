@extends('backend.users.layouts.mobile')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>
<style>
    /* Scrollbar hide karne ke liye (horizontal scroll ke liye) */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans" x-data="{ activeTab: 1 }">
    
    <div class="bg-slate-900 pt-8 pb-24 px-6 rounded-b-[2.5rem] shadow-xl relative overflow-hidden text-center">
        <div class="absolute top-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -ml-10 -mt-10"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">My Network</h2>
        
        <div class="mt-8 grid grid-cols-3 gap-3 relative z-10">
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Team</p>
                <p class="text-xl font-black text-white">{{ $totalTeam }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Active</p>
                <p class="text-xl font-black text-emerald-400">{{ $activeTeam }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Earned</p>
                <p class="text-[14px] font-black text-white mt-1.5">₹{{ number_format($totalCommission) }}</p>
            </div>
        </div>
    </div>

    <div class="px-5 -mt-14 relative z-20 space-y-5">

        <div>
            <h3 class="text-[10px] font-black text-slate-50 uppercase tracking-widest mb-3 px-2 drop-shadow-md">Team by Packages</h3>
            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($planCounts as $planName => $count)
                <div class="bg-white rounded-[1.5rem] p-4 shadow-xl border border-slate-50 min-w-[100px] shrink-0 text-center relative overflow-hidden">
                    @if($count > 0)
                        <div class="absolute top-0 right-0 w-8 h-8 bg-emerald-500/10 rounded-full blur-md -mr-2 -mt-2"></div>
                    @endif
                    <p class="text-[9px] font-bold text-slate-400 uppercase truncate">{{ $planName }}</p>
                    <p class="text-xl font-black {{ $count > 0 ? 'text-emerald-500' : 'text-slate-800' }} mt-1">{{ $count }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 relative overflow-hidden">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Your Invitation Link</h3>
            <div class="flex items-center gap-2">
                <div class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 flex-1 overflow-hidden">
                    <p class="text-[11px] font-bold text-slate-600 truncate" id="refLink">{{ $referralLink }}</p>
                </div>
                <button onclick="copyToClipboard()" class="bg-blue-600 text-white w-12 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-lg active:scale-90 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </button>
            </div>
            <div id="copyToast" class="hidden absolute top-4 right-4 bg-emerald-500 text-white text-[9px] font-black px-3 py-1 rounded shadow-md uppercase tracking-widest transition-all">Copied!</div>
        </div>

        <div class="flex gap-2 bg-slate-100 p-1.5 rounded-[1.5rem]">
            <button @click="activeTab = 1" class="flex-1 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeTab === 1 ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'">Level 1 ({{ $level1->count() }})</button>
            <button @click="activeTab = 2" class="flex-1 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeTab === 2 ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'">Level 2 ({{ $level2->count() }})</button>
            <button @click="activeTab = 3" class="flex-1 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeTab === 3 ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'">Level 3 ({{ $level3->count() }})</button>
        </div>

        <div class="space-y-3 pb-6">
            
            <div x-show="activeTab === 1" class="space-y-3" x-transition.opacity>
                @forelse($level1 as $member)
                    <x-team-member-card :member="$member" />
                @empty
                    <div class="text-center py-10 bg-white rounded-[2rem] border border-slate-50"><p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">No Level 1 Members</p></div>
                @endforelse
            </div>

            <div x-show="activeTab === 2" class="space-y-3 hidden" x-transition.opacity>
                @forelse($level2 as $member)
                    <x-team-member-card :member="$member" />
                @empty
                    <div class="text-center py-10 bg-white rounded-[2rem] border border-slate-50"><p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">No Level 2 Members</p></div>
                @endforelse
            </div>

            <div x-show="activeTab === 3" class="space-y-3 hidden" x-transition.opacity>
                @forelse($level3 as $member)
                    <x-team-member-card :member="$member" />
                @empty
                    <div class="text-center py-10 bg-white rounded-[2rem] border border-slate-50"><p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">No Level 3 Members</p></div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<script>
    function copyToClipboard() {
        var copyText = document.getElementById("refLink").innerText;
        navigator.clipboard.writeText(copyText).then(() => {
            let toast = document.getElementById('copyToast');
            toast.classList.remove('hidden');
            setTimeout(() => { toast.classList.add('hidden'); }, 2000);
        });
    }
</script>
@endsection