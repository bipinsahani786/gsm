<div x-data="{ expanded: false }" class="relative mt-3">
    
    <div class="absolute -left-6 top-8 w-6 h-px bg-slate-200"></div>
    <div class="absolute -left-6 top-0 w-px h-full bg-slate-200"></div>

    <div class="bg-white border border-slate-200 p-3 rounded-2xl flex items-center justify-between z-10 relative hover:border-blue-300 hover:shadow-md transition-all group">
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-xs font-black text-slate-600 shrink-0">
                {{ substr($member->name, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">{{ $member->name }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">UID: {{ $member->uid }}</p>
                    
                    @if($member->position_id)
                        <span class="text-[8px] bg-amber-50 text-amber-600 px-1.5 rounded font-black uppercase tracking-widest">{{ $member->position->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-[8px] px-2 py-1 rounded font-black uppercase tracking-widest hidden sm:block">
                {{ $member->level_id ? $member->level->name : 'Free' }}
            </span>
            
            @if($member->nestedDownlines->count() > 0)
                <button @click="expanded = !expanded" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-full text-slate-500 transition-colors">
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                </button>
            @else
                <div class="w-8 h-8"></div>
            @endif
        </div>
    </div>

    @if($member->nestedDownlines->count() > 0)
        <div x-show="expanded" x-collapse x-cloak class="ml-6 pl-4 border-l border-slate-200 pb-2">
            @foreach($member->nestedDownlines as $child)
                @include('backend.admins.pages.users.partials._tree_node', ['member' => $child])
            @endforeach
        </div>
    @endif
</div>