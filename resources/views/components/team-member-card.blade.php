@props(['member'])

<div class="bg-white rounded-[2rem] p-4 shadow-sm border border-slate-50 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center border-2 border-white shadow-sm shrink-0">
            <span class="text-sm font-black text-slate-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
        </div>
        <div>
            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-tight">{{ $member->name }}</h4>
            <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $member->created_at->format('d M Y') }}</p>
        </div>
    </div>
    
    <div class="text-right">
        @if($member->level_id)
            <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border border-emerald-100">Active</span>
            <p class="text-[8px] font-bold text-slate-400 uppercase mt-1.5">{{ $member->level->name ?? 'Plan' }}</p>
        @else
            <span class="bg-red-50 text-red-500 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border border-red-100">Inactive</span>
        @endif
    </div>
</div>