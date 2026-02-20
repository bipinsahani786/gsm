@extends('backend.admins.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row justify-between items-center gap-4 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">All Users</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Manage your network members</p>
        </div>

        <div class="relative z-10 w-full md:w-1/3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Name, UID or Mobile..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-4 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-4 pl-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Member Info</th>
                        <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact</th>
                        <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Plan/Level</th>
                        <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Balance</th>
                        <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Joined Date</th>
                        <th class="p-4 pr-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 font-black text-xs flex items-center justify-center shrink-0 border border-slate-200 shadow-sm">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $u->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">UID: {{ $u->uid }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-4">
                            <p class="text-xs font-bold text-slate-700">{{ $u->mobile ?? 'N/A' }}</p>
                        </td>

                        <td class="p-4">
                            @if($u->level_id)
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-widest">
                                    {{ $u->level->name }}
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-widest">
                                    Free
                                </span>
                            @endif
                        </td>

                        <td class="p-4">
                            <p class="text-sm font-black text-slate-800">
                                ₹{{ number_format(optional($u->wallet)->income_wallet + optional($u->wallet)->personal_wallet, 2) }}
                            </p>
                        </td>

                        <td class="p-4">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                {{ $u->created_at->format('d M Y') }}
                            </p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase mt-0.5">
                                {{ $u->created_at->format('h:i A') }}
                            </p>
                        </td>

                        <td class="p-4 pr-6 text-right">
                            <a href="{{ route('admin.users.show', $u->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 hover:border-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }} </div>
        @endif
    </div>

</div>
@endsection