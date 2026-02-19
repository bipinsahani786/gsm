@extends('backend.users.layouts.mobile')

@section('content')
<script src="//unpkg.com/alpinejs" defer></script>

<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans">
    
    <div class="bg-slate-900 pt-8 pb-16 px-6 rounded-b-[2.5rem] shadow-xl text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">Daily Tasks</h2>
        
        <div class="mt-6 flex justify-center gap-4 relative z-10">
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-center min-w-[120px]">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Today's Progress</p>
                <p class="text-lg font-black text-emerald-400 mt-1">{{ $todayTasksCompleted }} / {{ $level->daily_limit }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-center min-w-[120px]">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Task Reward</p>
                <p class="text-lg font-black text-white mt-1">₹{{ number_format($level->rate, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-5 -mt-8 relative z-20 space-y-4">

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-emerald-700 text-[10px] uppercase font-black tracking-widest text-center shadow-sm">
                🎉 {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-100 p-4 rounded-2xl text-red-700 text-[10px] uppercase font-black tracking-widest text-center shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($todayTasksCompleted < $level->daily_limit)
            <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-50 text-center" x-data="{ rating: 0 }">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Review & Earn</h3>
                
                <div class="w-full h-48 bg-slate-100 rounded-2xl overflow-hidden mb-6 shadow-inner relative">
                    <img src="{{ $randomImage }}" class="w-full h-full object-cover" alt="Task Image">
                    <div class="absolute top-2 right-2 bg-slate-900/60 backdrop-blur text-white text-[8px] font-bold px-2 py-1 rounded uppercase">Sponsored</div>
                </div>

                <div class="flex justify-center gap-2 mb-6">
                    <template x-for="star in 5">
                        <svg @click="rating = star" 
                             class="w-10 h-10 cursor-pointer transition-all active:scale-75"
                             :class="rating >= star ? 'text-yellow-400 fill-current drop-shadow-md' : 'text-slate-200 fill-current'"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </template>
                </div>

                <form action="{{ route('user.tasks.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rating" x-model="rating">
                    <button type="submit" 
                        class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg transition-all active:scale-95"
                        :class="rating > 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed'"
                        :disabled="rating === 0">
                        Submit Review
                    </button>
                </form>
            </div>
        @else
            <div class="bg-white rounded-[2rem] p-10 shadow-xl border border-slate-50 text-center mt-4">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-2">Great Job!</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                    You have completed all your tasks for today. <br>
                    <span class="text-emerald-600">Total Earned: ₹{{ number_format($level->daily_limit * $level->rate, 2) }}</span>
                </p>
                <a href="{{ route('user.dashboard') }}" class="mt-8 inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg">
                    Back to Dashboard
                </a>
            </div>
        @endif

    </div>
</div>
@endsection