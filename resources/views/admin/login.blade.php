<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - AM Merchandise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Glowing background elements -->
    <div class="absolute w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -top-12 -left-12 animate-pulse"></div>
    <div class="absolute w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -bottom-12 -right-12 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-md">
                AM Merchandise
            </h1>
            <p class="text-purple-200 text-sm mt-2">Dashboard Control Panel</p>
        </div>

        <!-- Form Glass Card -->
        <div class="glass-panel rounded-3xl shadow-2xl p-8 md:p-10">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Masuk ke Akun Anda</h2>

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-sm border border-red-200 flex items-start gap-2">
                    <span>⚠️</span>
                    <div>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Session Success Alert -->
            @if (session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl mb-6 text-sm border border-emerald-200">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="Contoh: user@am.com"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white/60 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 placeholder-gray-400"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white/60 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 placeholder-gray-400"
                    >
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-purple-500/30 transition duration-300 transform active:scale-[0.98] text-sm"
                    >
                        Masuk Ke Akun
                    </button>
                </div>
            </form>

            <!-- Demo accounts hint -->
            <div class="mt-6 p-4 rounded-2xl bg-purple-50 border border-purple-100 text-xs text-purple-800 space-y-2">
                <p class="font-bold flex items-center gap-1">🔑 <span>Demo Akun:</span></p>
                <div class="space-y-1">
                    <div><strong>Admin:</strong> admin@am.com / adminpassword123</div>
                    <div><strong>Customer:</strong> user@am.com / userpassword123</div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200/50 text-center">
                <a href="{{ route('home') }}" class="text-xs text-purple-600 hover:text-purple-700 font-semibold transition">
                    ← Kembali ke Toko
                </a>
            </div>
        </div>
    </div>
</body>
</html>
