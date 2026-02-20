<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Register | SP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 font-['Plus_Jakarta_Sans'] antialiased">

    <div class="min-h-screen flex flex-col">
        <div class="p-10 text-center">
            <h1 class="text-4xl font-black italic text-white tracking-tighter">SP<span class="text-red-500">.</span></h1>
            <p class="text-slate-400 text-xs mt-2 font-bold uppercase tracking-widest">Create New Account</p>
        </div>

        <div class="flex-1 bg-white rounded-t-[3rem] p-8 shadow-2xl">

          @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-[11px] uppercase tracking-wider font-black p-4 rounded-2xl text-center mb-6 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-600 text-[11px] uppercase tracking-wider font-black p-4 rounded-2xl text-center mb-6 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-[11px] font-bold p-4 rounded-2xl mb-6 shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif  
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 tracking-wider">Sponsor ID (Optional)</label>
                    <div class="relative mt-1">
                        <input type="text" name="rid" value="{{ request('ref') }}" placeholder="Enter Sponsor ID" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-red-500/20 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 tracking-wider">Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe" 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-red-500/20 outline-none">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 tracking-wider">Mobile Number</label>
                    <input type="number" name="mobile" required placeholder="91XXXXXXXX" 
                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-red-500/20 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Password</label>
                        <input type="password" name="password" required placeholder="••••••" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Confirm</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-bold py-5 rounded-2xl shadow-lg shadow-red-200 active:scale-95 transition-all mt-4">
                    Register Now
                </button>

                <p class="text-center text-xs text-slate-500 mt-6 font-medium">
                    Already a member? <a href="{{ route('login') }}" class="text-red-600 font-bold">Login Here</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>