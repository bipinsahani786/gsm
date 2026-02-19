<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login | DSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 font-['Plus_Jakarta_Sans'] antialiased">

    <div class="min-h-screen flex flex-col">
        <div class="p-12 text-center">
            <h1 class="text-4xl font-black italic text-white tracking-tighter">DSP<span class="text-red-500">.</span></h1>
            <p class="text-slate-400 text-[10px] mt-2 font-bold uppercase tracking-[0.2em]">Member Login</p>
        </div>

        <div class="flex-1 bg-white rounded-t-[3rem] p-10 shadow-2xl">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold mb-6 border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 tracking-wider">UID or Mobile</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="text" name="login_input" required placeholder="6-digit ID / Mobile" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-5 pl-12 pr-5 text-sm focus:ring-2 focus:ring-red-500/20 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 tracking-wider">Security Password</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-5 pl-12 pr-5 text-sm focus:ring-2 focus:ring-red-500/20 outline-none transition-all">
                    </div>
                    <div class="text-right mt-2">
                        <a href="#" class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter hover:text-red-500">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-5 rounded-2xl shadow-xl active:scale-95 transition-all mt-4">
                    Sign In Account
                </button>

                <div class="text-center pt-8">
                    <p class="text-slate-400 text-xs font-medium">New to DSP?</p>
                    <a href="{{ route('register') }}" class="inline-block mt-2 text-red-600 font-bold text-sm border-b-2 border-red-100 pb-1">Create Account Now</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>