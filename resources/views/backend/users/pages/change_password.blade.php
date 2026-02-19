@extends('backend.users.layouts.mobile')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans">
    
    <div class="bg-slate-900 pt-8 pb-16 px-6 rounded-b-[2.5rem] shadow-xl text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl -ml-12 -mt-12"></div>
        <h2 class="text-white text-xs font-black uppercase tracking-[0.2em] relative z-10">Security Center</h2>
        <p class="text-slate-400 text-[10px] font-bold uppercase mt-1 relative z-10">Update your account credentials</p>
    </div>

    <div class="max-w-md mx-auto px-5 -mt-10 relative z-20">
        
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="w-7 h-7 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-[11px] font-black text-emerald-700 uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-4 bg-red-50 border border-red-100 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <p class="text-[11px] font-black text-red-700 uppercase tracking-tight">
                    {{ session('error') ?? $errors->first() }}
                </p>
            </div>
        @endif

        <form action="{{ route('user.password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-6">
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Current Password
                    </label>
                    <input type="password" name="current_password" required 
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-slate-900/5 transition-all"
                        placeholder="••••••••">
                </div>

                <div class="border-t border-slate-50"></div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        New Password
                    </label>
                    <input type="password" name="new_password" required 
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-slate-900/5 transition-all"
                        placeholder="Min. 8 characters">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Confirm New Password
                    </label>
                    <input type="password" name="new_password_confirmation" required 
                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-sm font-bold outline-none ring-2 ring-transparent focus:ring-slate-900/5 transition-all"
                        placeholder="Repeat new password">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    Save New Password
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </form>

        <p class="text-center text-[9px] text-slate-400 font-bold uppercase mt-8 tracking-widest px-4">
            If you forgot your current password, please contact support for a manual reset.
        </p>

    </div>
</div>
@endsection