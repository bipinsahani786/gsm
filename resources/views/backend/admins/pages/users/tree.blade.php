@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Network Tree</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Viewing Downline Hierarchy</p>
        </div>
        <a href="{{ route('admin.users.show', $user->id) }}" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-colors">
            &larr; Back to Profile
        </a>
    </div>

    <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
        
        <div class="bg-slate-900 rounded-[1.5rem] p-4 flex items-center justify-between shadow-lg mb-6 relative z-20">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-white/10 text-white flex items-center justify-center font-black text-xl shadow-inner border border-white/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-white font-black text-lg uppercase tracking-tight">{{ $user->name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">UID: {{ $user->uid }} | Root Member</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                    {{ $user->level_id ? $user->level->name : 'Free' }}
                </span>
            </div>
        </div>

        <div class="pl-4 md:pl-8">
            @if($user->nestedDownlines->count() > 0)
                @foreach($user->nestedDownlines as $child)
                    @include('backend.admins.pages.users.partials._tree_node', ['member' => $child])
                @endforeach
            @else
                <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-3xl">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No team members found</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection