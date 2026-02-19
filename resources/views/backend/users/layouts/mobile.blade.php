<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'MLM Pro' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom); }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-[#F8F9FA] text-slate-900 antialiased selection:bg-red-100">

    <div class="sticky top-0 z-50 bg-slate-900 text-white px-5 py-4 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full border-2 border-red-500 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=1e293b&color=ef4444" alt="user">
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Welcome back</p>
                <p class="text-sm font-bold">{{ Auth::user()->name ?? 'DSP Developer' }}</p>
            </div>
        </div>
        {{-- <button class="relative p-2 bg-slate-800 rounded-full">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full border-2 border-slate-900"></span>
        </button> --}}
    </div>

    <main class="pb-28">
        @yield('content')
    </main>

    <nav class="fixed bottom-0 left-0 right-0 glass-nav border-t border-gray-100 z-[100] safe-area-bottom">
        <div class="flex justify-around items-center h-20 px-4">
            <x-mobile-nav-item href="/dashboard" label="Home" active="{{ request()->is('dashboard') }}" />
            <x-mobile-nav-item href="/funds" label="Funds" active="{{ request()->is('funds') }}" icon="wallet" />
            
            <div class="-mt-10">
                <button class="w-14 h-14 bg-red-600 rounded-2xl shadow-lg shadow-red-300 flex items-center justify-center text-white active:scale-90 transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>

            <x-mobile-nav-item href="/team" label="Team" icon="users" />
            <x-mobile-nav-item href="/profile" label="Profile"  active="{{ request()->is('profile') }}" icon="user" />
        </div>
    </nav>

</body>
</html>