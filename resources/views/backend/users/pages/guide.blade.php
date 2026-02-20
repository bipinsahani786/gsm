@extends('backend.users.layouts.mobile')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-32 font-sans">
    
    <div class="bg-slate-900 pt-8 pb-16 px-5 rounded-b-[2.5rem] shadow-xl text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl -ml-10 -mt-10"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mb-10"></div>
        
        <h2 class="text-white text-sm font-black uppercase tracking-[0.2em] relative z-10">How to Earn</h2>
        <p class="text-slate-400 text-[10px] font-bold mt-1.5 tracking-widest uppercase relative z-10">Learn & Grow your income</p>
    </div>

    <div class="px-4 -mt-10 relative z-20 space-y-6">

        @if($banners->count() > 0)
        <div>
            <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide snap-x">
                @foreach($banners as $banner)
                <div class="w-full min-w-full snap-center shrink-0">
                    <div class="bg-white p-1.5 rounded-[1.5rem] shadow-lg border border-slate-50">
                        <img src="{{ asset($banner->image) }}" class="w-full h-40 object-cover rounded-[1.2rem]">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div>
            <div class="flex items-center justify-between px-2 mb-4">
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Income Categories</h3>
                <span class="bg-amber-100 text-amber-600 text-[8px] font-black px-2 py-1 rounded uppercase tracking-widest">{{ $methods->count() }} Guides</span>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                @forelse($methods as $method)
                <div class="bg-white rounded-[1.5rem] p-3 shadow-sm border border-slate-100 flex flex-col h-full relative overflow-hidden group">
                    
                    <div class="w-full h-28 rounded-xl overflow-hidden bg-slate-50 mb-3 relative border border-slate-50">
                        <img src="{{ asset($method->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-2 right-2 bg-red-500/90 backdrop-blur-sm text-white text-[8px] font-black px-2 py-1 rounded-lg uppercase tracking-widest shadow-sm">
                            PDF
                        </div>
                    </div>
                    
                    <h4 class="font-black text-slate-800 text-[11px] uppercase tracking-tight leading-tight flex-1 px-1">
                        {{ $method->title }}
                    </h4>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ asset($method->pdf_file) }}" target="_blank" class="flex-1 bg-slate-900 text-white text-center py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all shadow-md">
                            Read Now
                        </a>
                        <a href="{{ asset($method->pdf_file) }}" download class="w-10 h-10 bg-slate-50 border border-slate-100 text-slate-600 rounded-xl flex items-center justify-center active:scale-95 transition-all shrink-0 hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-10 bg-white rounded-[1.5rem] border border-slate-100">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Coming Soon</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection