@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-black text-slate-900 uppercase">App Contents</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Manage Banners & Earning Guides</p>
        </div>
    </div>

   @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-[11px] uppercase tracking-wider font-black p-4 rounded-2xl mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 text-[11px] font-bold p-4 rounded-2xl mb-6 shadow-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-6">Upload New Banner</h3>
                
                <form action="{{ route('admin.guides.banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Banner Image (16:9 ratio recommended)</label>
                        <input type="file" name="image" required class="w-full text-xs text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 border border-slate-100 rounded-2xl p-1">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-200 active:scale-95 transition-all hover:bg-blue-700">
                        Upload Banner
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Active Banners ({{ $banners->count() }})</h3>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($banners as $banner)
                        <div class="relative group rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm">
                            <img src="{{ asset($banner->image) }}" class="w-full h-24 object-cover">
                            
                            <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.guides.banner.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?');">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No Banners Uploaded</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-6">Add Earning Guide</h3>
                
                <form action="{{ route('admin.guides.method.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Title / Category Name</label>
                        <input type="text" name="title" required placeholder="e.g. YouTube Referral Guide" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Thumbnail Image</label>
                        <input type="file" name="thumbnail" required class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 border border-slate-100 rounded-xl p-1">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">PDF File (Max 10MB)</label>
                        <input type="file" name="pdf_file" accept=".pdf" required class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 border border-slate-100 rounded-xl p-1">
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-indigo-200 active:scale-95 transition-all hover:bg-indigo-700 mt-2">
                        Publish Guide
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Published Guides ({{ $methods->count() }})</h3>
                
                <div class="space-y-3">
                    @forelse($methods as $method)
                        <div class="flex items-center gap-4 p-3 border border-slate-100 rounded-2xl hover:bg-slate-50 transition-colors">
                            <img src="{{ asset($method->thumbnail) }}" class="w-16 h-12 object-cover rounded-xl shadow-sm shrink-0">
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-slate-800 text-xs truncate uppercase tracking-tight">{{ $method->title }}</h4>
                                <a href="{{ asset($method->pdf_file) }}" target="_blank" class="text-[9px] font-bold text-red-500 uppercase tracking-widest hover:underline mt-0.5 inline-block">View PDF File</a>
                            </div>

                            <form action="{{ route('admin.guides.method.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Delete this guide?');">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No Guides Uploaded</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection