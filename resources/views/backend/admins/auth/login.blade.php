<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | DSP Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black italic tracking-tighter text-slate-900">DSP<span class="text-red-600">.</span>ADMIN</h1>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Management Console</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-slate-900"></div>

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Administrator Email</label>
                    <input type="email" name="email" required placeholder="admin@DSP.com" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-2 focus:ring-slate-900/10 outline-none transition-all mt-1">
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 text-sm focus:ring-2 focus:ring-slate-900/10 outline-none transition-all mt-1">
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-slate-200 hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Secure System Login
                </button>
            </form>
        </div>
        
        <p class="text-center mt-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest italic">&copy; 2026 DSP Systems Core</p>
    </div>
</body>
</html>