<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false, profileOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SP Admin' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 antialiased overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 z-[60] lg:hidden backdrop-blur-sm transition-opacity"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 w-72 bg-[#0F172A] text-white z-[70] transition-transform duration-300 ease-in-out lg:static lg:inset-0 shrink-0 flex flex-col border-r border-slate-800">

            <div class="h-20 flex items-center justify-between px-8 border-b border-slate-800/50">
                <span class="text-2xl font-black italic tracking-tighter text-white">
                    SP<span class="text-red-500">.</span>
                </span>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar text-[13px]">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-6 mb-3">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/dashboard') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/users*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    All Users
                </a>

                <a href="{{ route('admin.guides.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/guides*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    App Guides
                </a>
                <a href="{{ route('admin.popups.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/popups*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    App Popups
                </a>



                <a href="{{ route('admin.deposits.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/deposits*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    Deposit Requests
                </a>

                <a href="{{ route('admin.withdrawals.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/withdrawals*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                    Withdraw Requests
                </a>

                <a href="{{ route('admin.levels.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/levels*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    VIP Levels
                </a>

                <a href="{{ route('admin.rewards.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/rewards*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="gift" class="w-4 h-4"></i>
                    Rewards
                </a>

                <a href="{{ route('admin.positions.index') ?? '#' }}"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/positions*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                    Positions
                </a>

                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-6 mb-3 mt-8">Management</p>

                <a href="/admin/settings/withdrawals"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/settings/withdrawals*') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                    Payout Settings
                </a>

                <a href="/admin/settings"
                    class="flex items-center gap-3 px-6 py-3.5 rounded-2xl transition-all {{ request()->is('admin/settings') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/50 font-medium' }}">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    System Settings
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800/50 bg-slate-900/50">
                <div class="flex items-center gap-3 px-4 py-3 bg-slate-800/40 rounded-2xl border border-slate-700/50">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-xs font-black uppercase shadow-inner">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 lg:px-10 shrink-0 shadow-sm z-40">
                <button @click="sidebarOpen = true" class="lg:hidden p-2.5 bg-slate-50 rounded-xl text-slate-600 active:scale-95 transition-all border border-slate-100">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="flex items-center gap-2">
                    <span class="hidden md:inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Server Operational
                    </span>
                </div>

                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" 
                        class="flex items-center gap-3 p-1.5 pr-4 hover:bg-slate-50 rounded-2xl transition-all border border-transparent hover:border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black uppercase shadow-lg shadow-slate-900/10">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-black text-slate-900">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Manage Account</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="profileOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="profileOpen" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-56 bg-white rounded-[2rem] shadow-2xl border border-slate-100 py-3 z-50">
                        
                        <div class="px-6 py-4 border-b border-slate-50 mb-2">
                            <p class="text-xs font-black text-slate-900">Account</p>
                            <p class="text-[10px] text-slate-400 font-medium truncate mt-0.5">{{ Auth::user()->email ?? 'admin@SP.com' }}</p>
                        </div>

                        <a href="/admin/settings" class="flex items-center gap-3 px-6 py-3 text-xs font-bold text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            My Profile
                        </a>
                        <a href="/admin/settings" class="flex items-center gap-3 px-6 py-3 text-xs font-bold text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            Security Settings
                        </a>

                        <div class="px-4 mt-2">
                            <form action="{{ route('admin.logout') ?? '#' }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-red-50 text-red-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar bg-[#F8FAFC]">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>