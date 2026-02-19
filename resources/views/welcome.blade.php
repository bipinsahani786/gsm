<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP | Next-Gen Earning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-bg {
            background-color: #0f172a;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }
        .text-gradient {
            background: linear-gradient(to right, #ef4444, #f87171);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-lg border-b border-slate-100 transition-all duration-300" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="#" class="text-2xl font-black italic tracking-tighter text-slate-900">
                SP<span class="text-red-600">.</span>
            </a>
            
            <div class="hidden lg:flex items-center space-x-8 text-sm font-bold text-slate-600">
                <a href="#home" class="hover:text-red-600 transition-colors">Home</a>
                <a href="#about" class="hover:text-red-600 transition-colors">About</a>
                <a href="#features" class="hover:text-red-600 transition-colors">Features</a>
                <a href="#app" class="hover:text-red-600 transition-colors">Download App</a>
                <a href="#contact" class="hover:text-red-600 transition-colors">Contact</a>
            </div>

            <div class="hidden lg:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-900 px-4 py-2 hover:bg-slate-50 rounded-full transition-all">Login</a>
                <a href="{{ route('register') }}" class="bg-slate-900 text-white text-sm font-bold px-6 py-2.5 rounded-full shadow-lg hover:bg-red-600 transition-all">Get Started</a>
            </div>

            <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-slate-900 text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div x-show="mobileMenu" x-transition class="lg:hidden bg-white border-b border-slate-100 p-6 space-y-4">
            <a href="#" class="block text-sm font-bold text-slate-600">Home</a>
            <a href="#features" class="block text-sm font-bold text-slate-600">Features</a>
            <a href="#app" class="block text-sm font-bold text-slate-600">Download App</a>
            <div class="pt-4 flex flex-col gap-3">
                <a href="{{ route('login') }}" class="w-full text-center border border-slate-200 py-3 rounded-xl font-bold">Login</a>
                <a href="{{ route('register') }}" class="w-full text-center bg-red-600 text-white py-3 rounded-xl font-bold">Sign Up</a>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-bg min-h-[90vh] flex items-center pt-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center z-10">
            <div class="space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 bg-white/10 border border-white/10 px-4 py-1.5 rounded-full backdrop-blur-md">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-widest">Version 3.0 Live</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1]">
                    Unlock Your <br><span class="text-gradient">Financial Future</span>
                </h1>
                <p class="text-slate-400 text-lg max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    SP is the world's leading decentralized earning platform. Complete tasks, refer friends, and withdraw daily directly to your UPI or Crypto wallet.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="bg-red-600 text-white font-bold py-4 px-10 rounded-2xl shadow-xl shadow-red-600/20 hover:-translate-y-1 transition-transform">
                        Start Earning Now
                    </a>
                    <a href="#how-it-works" class="bg-white/10 backdrop-blur-md border border-white/10 text-white font-bold py-4 px-10 rounded-2xl hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                        <i class="fa-regular fa-circle-play"></i> Watch Video
                    </a>
                </div>
                
                <div class="pt-6 flex items-center justify-center lg:justify-start gap-4 text-sm font-bold text-slate-500">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-check-circle text-green-500"></i> Instant Withdrawal</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-check-circle text-green-500"></i> SSL Secure</span>
                </div>
            </div>
            
            <div class="relative hidden lg:block">
                <div class="absolute inset-0 bg-red-500/30 blur-[100px] rounded-full"></div>
                <img src="https://img.freepik.com/free-vector/gradient-crypto-portfolio-app-template_23-2149214754.jpg?w=1380" alt="App Dashboard" class="relative z-10 rounded-[2.5rem] shadow-2xl border-4 border-slate-800 rotate-[-5deg] hover:rotate-0 transition-all duration-500">
                
                <div class="absolute -bottom-10 -left-10 bg-white p-6 rounded-3xl shadow-2xl z-20 animate-bounce" style="animation-duration: 3s;">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">
                            <i class="fa-solid fa-arrow-up"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase">Profit Today</p>
                            <p class="text-xl font-black text-slate-900">+ ₹4,250.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-slate-900 py-6 border-b border-slate-800 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-800/50">
                <div>
                    <p class="text-3xl font-black text-white">1.2M+</p>
                    <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mt-1">Verified Users</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-white">₹45Cr+</p>
                    <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mt-1">Total Payouts</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-white">24/7</p>
                    <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mt-1">Support Active</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-white">4.8 <span class="text-sm text-yellow-500"><i class="fa-solid fa-star"></i></span></p>
                    <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mt-1">User Rating</p>
                </div>
            </div>
        </div>
    </div>

    <section id="about" class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div class="order-2 md:order-1 relative">
                <img src="https://img.freepik.com/free-photo/cheerful-young-asian-businesswoman-using-smartphone_171337-738.jpg" alt="Happy User" class="rounded-[3rem] shadow-2xl">
                <div class="absolute -bottom-6 -right-6 bg-red-600 text-white p-8 rounded-[2rem] shadow-xl">
                    <p class="text-4xl font-black">5+</p>
                    <p class="text-sm font-bold uppercase opacity-80">Years Experience</p>
                </div>
            </div>
            <div class="order-1 md:order-2 space-y-6">
                <span class="text-red-600 font-bold text-sm uppercase tracking-widest">Who We Are</span>
                <h2 class="text-4xl font-black text-slate-900 leading-tight">We help you generate <br>Passive Income.</h2>
                <p class="text-slate-500 leading-relaxed">
                    SP is built on the core principle of financial transparency. We connect businesses who need tasks done with users who want to earn. It's a simple ecosystem where everyone wins.
                </p>
                <ul class="space-y-4 pt-4">
                    <li class="flex items-center gap-4">
                        <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600"><i class="fa-solid fa-check"></i></span>
                        <span class="font-bold text-slate-700">Daily Task Updates</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600"><i class="fa-solid fa-check"></i></span>
                        <span class="font-bold text-slate-700">Automated UPI Withdrawals</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600"><i class="fa-solid fa-check"></i></span>
                        <span class="font-bold text-slate-700">No Hidden Charges</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto text-center mb-16">
            <span class="text-red-600 font-bold text-sm uppercase tracking-widest">Our Features</span>
            <h2 class="text-4xl font-black text-slate-900 mt-2">Why SP is #1 Choice</h2>
        </div>
        
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Fast Withdrawals</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Get your money in your bank account within minutes via IMPS/UPI.</p>
            </div>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Referral Bonus</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Earn up to 15% commission from your team's earnings up to 3 levels.</p>
            </div>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Secure System</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Your data and funds are protected by enterprise-grade security encryption.</p>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-24 px-6 bg-white relative overflow-hidden">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900">How to Start Earning?</h2>
                <p class="text-slate-500 mt-4">Just 3 simple steps to financial freedom.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-12 left-0 w-full h-1 bg-slate-100 -z-10"></div>

                <div class="text-center bg-white">
                    <div class="w-24 h-24 mx-auto bg-slate-900 text-white rounded-full flex items-center justify-center text-3xl font-black border-8 border-white shadow-xl mb-6">1</div>
                    <h3 class="text-xl font-bold mb-2">Register</h3>
                    <p class="text-slate-500 text-sm px-4">Create a free account using your mobile number.</p>
                </div>
                <div class="text-center bg-white">
                    <div class="w-24 h-24 mx-auto bg-red-600 text-white rounded-full flex items-center justify-center text-3xl font-black border-8 border-white shadow-xl mb-6">2</div>
                    <h3 class="text-xl font-bold mb-2">Activate Plan</h3>
                    <p class="text-slate-500 text-sm px-4">Choose a VIP plan that suits your budget and start tasks.</p>
                </div>
                <div class="text-center bg-white">
                    <div class="w-24 h-24 mx-auto bg-green-500 text-white rounded-full flex items-center justify-center text-3xl font-black border-8 border-white shadow-xl mb-6">3</div>
                    <h3 class="text-xl font-bold mb-2">Withdraw</h3>
                    <p class="text-slate-500 text-sm px-4">Receive earnings daily directly to your bank account.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="app" class="py-24 px-6 bg-[#0f172a] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-600/20 rounded-full blur-[120px]"></div>
        
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center relative z-10">
            <div class="space-y-8">
                <span class="bg-red-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">Mobile First</span>
                <h2 class="text-5xl font-black text-white leading-tight">
                    Download the <br><span class="text-red-500">Official SP App</span>
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed">
                    Access your dashboard anytime, anywhere. Get real-time notifications for payouts and new tasks. Available for Android and iOS.
                </p>
                
                <div class="flex flex-wrap gap-4 pt-4">
                    <button class="flex items-center gap-3 bg-white text-slate-900 px-6 py-3 rounded-xl hover:bg-slate-200 transition-colors">
                        <i class="fa-brands fa-google-play text-2xl text-green-600"></i>
                        <div class="text-left">
                            <p class="text-[10px] uppercase font-bold text-slate-500">Get it on</p>
                            <p class="font-black text-sm leading-none">Google Play</p>
                        </div>
                    </button>
                    <button class="flex items-center gap-3 bg-slate-800 text-white px-6 py-3 rounded-xl hover:bg-slate-700 transition-colors border border-slate-700">
                        <i class="fa-brands fa-apple text-2xl"></i>
                        <div class="text-left">
                            <p class="text-[10px] uppercase font-bold text-slate-400">Download on</p>
                            <p class="font-black text-sm leading-none">App Store</p>
                        </div>
                    </button>
                </div>
                
                <div class="pt-6 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img src="https://randomuser.me/api/portraits/women/1.jpg" class="w-10 h-10 rounded-full border-2 border-slate-900">
                        <img src="https://randomuser.me/api/portraits/men/2.jpg" class="w-10 h-10 rounded-full border-2 border-slate-900">
                        <img src="https://randomuser.me/api/portraits/women/3.jpg" class="w-10 h-10 rounded-full border-2 border-slate-900">
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-xs font-bold text-white">+2k</div>
                    </div>
                    <p class="text-slate-400 text-sm font-bold">Downloaded today</p>
                </div>
            </div>

            <div class="relative flex justify-center">
                <div class="relative z-10 w-72 bg-black rounded-[3rem] border-8 border-slate-800 overflow-hidden shadow-2xl">
                    <img src="https://cdn.dribbble.com/users/3943311/screenshots/17395726/media/5323a669767f402e128148386377227d.png?resize=400x300&vertical=center" class="w-full h-full object-cover">
                </div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/10 rounded-full"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-white/5 rounded-full"></div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6 bg-slate-50">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-slate-900">Live Payouts</h2>
                <p class="text-slate-500">Real-time withdrawals processed by our system.</p>
            </div>
            
            <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex justify-between font-bold text-slate-400 text-xs uppercase tracking-widest">
                    <span>User</span>
                    <span>Method</span>
                    <span>Amount</span>
                    <span>Status</span>
                </div>
                <div class="divide-y divide-slate-50">
                    <div class="p-5 flex justify-between items-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">A</div>
                            <span class="font-bold text-slate-700">Amit K.</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">UPI (PhonePe)</span>
                        <span class="font-black text-slate-900">₹ 2,450.00</span>
                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-1 rounded-md font-bold uppercase">Success</span>
                    </div>
                    <div class="p-5 flex justify-between items-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">R</div>
                            <span class="font-bold text-slate-700">Rahul S.</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">USDT (TRC20)</span>
                        <span class="font-black text-slate-900">$ 45.00</span>
                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-1 rounded-md font-bold uppercase">Success</span>
                    </div>
                    <div class="p-5 flex justify-between items-center hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold text-xs">P</div>
                            <span class="font-bold text-slate-700">Priya M.</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500">Paytm</span>
                        <span class="font-black text-slate-900">₹ 850.00</span>
                        <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-1 rounded-md font-bold uppercase">Processing</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto text-center mb-16">
            <h2 class="text-4xl font-black text-slate-900">What Users Say</h2>
        </div>
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="glass-card p-8 rounded-[2rem] border border-slate-100">
                <div class="text-yellow-400 text-lg mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="text-slate-600 italic mb-6">"Best earning app I have ever used. Withdrawal is super fast and customer support is very helpful."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-10 h-10 rounded-full">
                    <span class="font-bold text-slate-900">Vikram Singh</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] border border-slate-100">
                <div class="text-yellow-400 text-lg mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="text-slate-600 italic mb-6">"I have earned over ₹50,000 in just 2 months. The referral system is amazing for extra income."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-10 h-10 rounded-full">
                    <span class="font-bold text-slate-900">Neha Sharma</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] border border-slate-100">
                <div class="text-yellow-400 text-lg mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i></div>
                <p class="text-slate-600 italic mb-6">"Very easy to use interface. I recommend everyone to join SP Digital Platform."</p>
                <div class="flex items-center gap-4">
                    <img src="https://randomuser.me/api/portraits/men/86.jpg" class="w-10 h-10 rounded-full">
                    <span class="font-bold text-slate-900">Rohan Das</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6 bg-slate-50">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-black text-slate-900 text-center mb-10">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <details class="bg-white p-6 rounded-2xl border border-slate-200 cursor-pointer group">
                    <summary class="flex justify-between items-center font-bold text-slate-800">
                        Is it free to join?
                        <span class="text-red-600 transition-transform group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                    </summary>
                    <p class="mt-4 text-slate-500 text-sm">Yes, registration is completely free. You can browse plans and start with a basic package.</p>
                </details>
                
                <details class="bg-white p-6 rounded-2xl border border-slate-200 cursor-pointer group">
                    <summary class="flex justify-between items-center font-bold text-slate-800">
                        What is minimum withdrawal?
                        <span class="text-red-600 transition-transform group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                    </summary>
                    <p class="mt-4 text-slate-500 text-sm">The minimum withdrawal amount is just ₹150. You can withdraw daily between 10 AM to 6 PM.</p>
                </details>

                <details class="bg-white p-6 rounded-2xl border border-slate-200 cursor-pointer group">
                    <summary class="flex justify-between items-center font-bold text-slate-800">
                        How can I contact support?
                        <span class="text-red-600 transition-transform group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                    </summary>
                    <p class="mt-4 text-slate-500 text-sm">We have 24/7 WhatsApp and Telegram support available directly from your dashboard.</p>
                </details>
            </div>
        </div>
    </section>

    <section class="py-24 px-6">
        <div class="max-w-6xl mx-auto bg-gradient-to-r from-red-600 to-red-500 rounded-[3rem] p-12 md:p-20 text-center text-white relative overflow-hidden shadow-2xl shadow-red-500/30">
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">Don't Miss This Opportunity</h2>
                <p class="text-red-100 mb-10 max-w-xl mx-auto text-lg">Join thousands of successful users who are earning daily with SP. Secure your position now!</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-white text-red-600 font-black py-4 px-12 rounded-full shadow-xl hover:scale-105 transition-transform">
                        Create Free Account
                    </a>
                    <a href="{{ route('login') }}" class="bg-red-700/50 backdrop-blur-md border border-white/20 text-white font-bold py-4 px-12 rounded-full hover:bg-red-700 transition-colors">
                        Member Login
                    </a>
                </div>
            </div>
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white pt-20 pb-10 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-1 md:col-span-2">
                <div class="text-2xl font-black italic tracking-tighter mb-6">
                    SP<span class="text-red-500">.</span>
                </div>
                <p class="text-slate-400 max-w-sm leading-relaxed text-sm">
                    SP is a registered digital marketing and task management platform aimed at providing financial independence to youth.
                </p>
                <div class="flex gap-4 mt-6">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-600 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-600 transition-colors"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-red-600 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Quick Links</h4>
                <ul class="space-y-3 text-slate-400 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Business Plan</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Legal Documents</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Contact Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Contact</h4>
                <ul class="space-y-3 text-slate-400 text-sm">
                    <li><i class="fa-solid fa-envelope mr-2"></i> support@SP.com</li>
                    <li><i class="fa-solid fa-phone mr-2"></i> +91 98765 43210</li>
                    <li><i class="fa-solid fa-location-dot mr-2"></i> Patna, Bihar, India</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-8 border-t border-slate-800 text-center text-slate-500 text-xs">
            <p>&copy; 2026 SP Digital Platform. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>