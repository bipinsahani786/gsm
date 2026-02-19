@props(['label' => '', 'color' => 'slate', 'icon' => 'plus'])

<button {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center bg-white py-4 rounded-2xl shadow-md border border-gray-50 active:scale-95 transition-all']) }}>
    <div class="bg-{{ $color == 'red' ? 'red' : 'slate' }}-50 p-2.5 rounded-xl mb-2">
        @if($label == 'Recharge')
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        @elseif($label == 'Withdraw')
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        @else
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        @endif
    </div>
    <span class="text-[11px] font-bold text-slate-700">{{ $label }}</span>
</button>