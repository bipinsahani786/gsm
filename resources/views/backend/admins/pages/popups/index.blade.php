@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-black text-slate-900 uppercase">Home Popup</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Manage User Dashboard Announcement</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 font-black text-xs p-4 rounded-2xl mb-6 uppercase tracking-widest">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-6">Create New Popup</h3>
            
            <form action="{{ route('admin.popups.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Popup Image (Required)</label>
                    <input type="file" name="image" required class="w-full text-xs text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-600 border border-slate-100 rounded-2xl p-1">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Title (Optional)</label>
                    <input type="text" name="title" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Redirect Link (Optional)</label>
                    <input type="text" name="link" placeholder="https://" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none">
                </div>
                
                <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg active:scale-95 transition-all">
                    Publish Popup
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm h-fit">
            <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Popup History</h3>
            <div class="space-y-4">
                @foreach($popups as $popup)
                    <div class="flex items-center gap-4 p-3 border border-slate-100 rounded-2xl {{ $popup->status ? 'bg-emerald-50/30 border-emerald-100' : 'bg-slate-50' }}">
                        <img src="{{ asset($popup->image) }}" class="w-16 h-16 object-cover rounded-xl shadow-sm">
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 text-sm truncate">{{ $popup->title ?? 'No Title' }}</h4>
                            @if($popup->status)
                                <span class="text-[8px] font-black text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded uppercase tracking-widest">Live Now</span>
                            @else
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Inactive</span>
                            @endif
                        </div>
                        <form action="{{ route('admin.popups.destroy', $popup->id) }}" method="POST" onsubmit="return confirm('Delete this?');">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 rounded-full bg-white border border-slate-200 text-red-500 hover:bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection