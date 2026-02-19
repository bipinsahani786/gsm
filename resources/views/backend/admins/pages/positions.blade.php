@extends('backend.admins.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto pb-20">

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-black text-slate-900 uppercase">Salary Positions</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Manage Monthly Salaries</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 sticky top-6 shadow-xl shadow-slate-200/50">
                <h3 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-6">Add Position</h3>
                <form action="{{ route('admin.positions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Position Name</label>
                        <input type="text" name="name" placeholder="e.g. Team Leader" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Monthly Salary (₹)</label>
                        <input type="number" name="salary" placeholder="31000" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Team Condition (Text)</label>
                        <input type="text" name="team_condition" placeholder="e.g. 20 A-level members" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Required Total Members</label>
                        <input type="number" name="required_members" placeholder="150" class="w-full bg-slate-50 border-none rounded-2xl py-3 px-5 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-2 mb-1 block">Icon</label>
                        <input type="file" name="icon" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100">
                    </div>
                    <button type="submit" class="w-full bg-amber-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-amber-200 active:scale-95 transition-all hover:bg-amber-600">
                        Add Position
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                
                <div class="bg-gradient-to-r from-amber-200 to-amber-400 p-4 grid grid-cols-5 text-center gap-2">
                    <div class="font-black text-slate-900 uppercase tracking-widest text-xs col-span-2 text-left pl-4">Position</div>
                    <div class="font-black text-slate-900 uppercase tracking-widest text-xs">Salary</div>
                    <div class="font-black text-slate-900 uppercase tracking-widest text-xs">Status</div>
                    <div class="font-black text-slate-900 uppercase tracking-widest text-xs">Action</div>
                </div>

                <div class="divide-y divide-slate-50">
                    @foreach($positions as $pos)
                    <div class="p-4 grid grid-cols-5 items-center text-center gap-2 hover:bg-slate-50 transition-colors group {{ $pos->status ? '' : 'opacity-60 grayscale' }}">
                        
                        <div class="text-left pl-4 col-span-2">
                            <div class="flex items-center gap-3">
                                @if($pos->icon)
                                    <img src="{{ asset($pos->icon) }}" class="w-10 h-10 object-cover rounded-full border border-slate-100">
                                @else
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold text-xs border border-amber-200">
                                        {{ substr($pos->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $pos->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $pos->team_condition }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="font-black text-lg text-slate-900">
                            ₹{{ number_format($pos->salary) }}
                        </div>

                        <div class="flex justify-center">
                            <form action="{{ route('admin.positions.status', $pos->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border
                                    {{ $pos->status ? 'bg-green-50 text-green-600 border-green-100 hover:bg-red-50 hover:text-red-600 hover:border-red-100' : 'bg-red-50 text-red-600 border-red-100 hover:bg-green-50 hover:text-green-600 hover:border-green-100' }}">
                                    {{ $pos->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </div>

                        <div class="flex justify-center">
                            <form action="{{ route('admin.positions.destroy', $pos->id) }}" method="POST" onsubmit="return confirm('Delete this position?');">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-all border border-slate-100 hover:border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

@endsection