@extends('backend.users.layouts.mobile')

@section('content')

<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6">
    
    <div class="relative mb-8">
        <div class="absolute inset-0 bg-red-100 rounded-full blur-xl opacity-50 animate-pulse"></div>
        <div class="relative w-24 h-24 bg-white rounded-[2rem] shadow-xl border border-slate-100 flex items-center justify-center">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="absolute -top-2 -right-2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-full border-2 border-white">
                V2.0
            </div>
        </div>
    </div>

    <h2 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">
        Funds <span class="text-red-600">Pro</span>
    </h2>
    
    <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto mb-8 font-medium">
        We are building a powerful Funds Management system for you. Track earnings, manage withdrawals & deposits seamlessly.
    </p>

    <div class="w-full max-w-[200px] bg-slate-100 rounded-full h-2 mb-2 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full w-[85%] animate-pulse"></div>
    </div>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-10">85% Completed</p>

    <button class="bg-slate-900 text-white px-8 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-slate-200 active:scale-95 transition-all">
        Notify When Live
    </button>

</div>

@endsection