@props(['href' => '#', 'label' => '', 'active' => false, 'icon' => 'home'])

<a href="{{ $href }}" class="flex flex-col items-center justify-center gap-1 group w-full">

    <div
        class="p-2 rounded-xl transition-all duration-300 {{ $active ? 'text-red-600 bg-red-50' : 'text-slate-400 hover:text-slate-600' }}">

        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">

            @if ($icon == 'home')
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            @elseif($icon == 'clipboard-check')
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 14l2 2 4-4"></path>
                </svg>
            @elseif($icon == 'users' || $icon == 'team')
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            @elseif($icon == 'user' || $icon == 'profile')
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            @endif
        </svg>
    </div>

    <span class="text-[10px] font-bold {{ $active ? 'text-red-600' : 'text-slate-400' }}">
        {{ $label }}
    </span>
</a>
