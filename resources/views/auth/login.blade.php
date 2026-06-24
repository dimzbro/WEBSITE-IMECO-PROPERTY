<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Beltway Office Park</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center relative overflow-hidden" style="background-color: #0F172A; font-family: 'Plus Jakarta Sans', sans-serif; color: #ffffff;">

    <!-- Decorative Gradients -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full opacity-20 filter blur-[120px]" style="background: radial-gradient(circle, #1E3A8A, transparent);"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full opacity-15 filter blur-[120px]" style="background: radial-gradient(circle, #D4AF37, transparent);"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md mx-4 p-8 rounded-2xl border border-white/10 backdrop-blur-md shadow-2xl bg-white/5 transition-all duration-300 hover:border-white/20">
        
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 no-underline mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #1E3A8A, #D4AF37);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </a>
            <h2 class="text-2xl font-black tracking-tight text-white mb-1">Welcome Back</h2>
            <p class="text-sm text-white/60">Beltway Office Park Admin Portal</p>
        </div>

        <!-- Form -->
        <form action="{{ url('/login') }}" method="POST" class="space-y-5">
            @csrf

            @if ($errors->has('login_error'))
                <div class="p-3 rounded-xl text-xs bg-red-500/20 border border-red-500/30 text-red-300">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-white/70 mb-2">Email Address</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                       class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white placeholder-white/40 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 transition-all duration-200 outline-none">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-white/70 mb-2">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white placeholder-white/40 focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 transition-all duration-200 outline-none">
            </div>

            <div class="flex items-center justify-between text-xs text-white/60">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="rounded border-white/10 bg-white/5 text-[#1E3A8A] focus:ring-0 focus:ring-offset-0">
                    <span>Remember me</span>
                </label>
                <a href="#" class="hover:text-white transition-colors">Forgot password?</a>
            </div>

            <button type="submit" 
                    class="w-full py-3 rounded-xl font-bold text-sm tracking-wide text-white transition-all duration-300 focus:outline-none"
                    style="background-color: #1E3A8A; box-shadow: 0 4px 14px rgba(30, 58, 138, 0.4);"
                    onmouseover="this.style.backgroundColor='#0F172A'; this.style.boxShadow='0 4px 14px rgba(15, 23, 42, 0.4)';"
                    onmouseout="this.style.backgroundColor='#1E3A8A'; this.style.boxShadow='0 4px 14px rgba(30, 58, 138, 0.4)';">
                Sign In
            </button>
        </form>

        <!-- Back to site -->
        <div class="text-center mt-6">
            <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 text-xs text-white/60 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to main site
            </a>
        </div>
    </div>
</body>
</html>
