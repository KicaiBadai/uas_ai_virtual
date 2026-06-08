<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel AM Merchandise</title>
    <script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .sidebar-active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
            border-left: 4px solid #8b5cf6;
            color: #8b5cf6;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Header -->
    <header class="md:hidden bg-gray-900 text-white px-4 py-4 flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">AM Merchandise</h1>
        <button onclick="toggleMobileSidebar()" class="text-white hover:text-purple-400 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </header>

    <!-- Sidebar Wrapper -->
    <aside id="sidebar" class="hidden md:flex flex-col w-full md:w-64 bg-gray-900 text-gray-300 min-h-screen p-6 flex-shrink-0 z-30 transition-all duration-300">
        <!-- Logo -->
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl font-extrabold text-white tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">
                AM Admin
            </h2>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-semibold">Store Control</p>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-2 flex-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition font-medium text-sm {{ Route::is('admin.dashboard') ? 'sidebar-active text-white' : '' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition font-medium text-sm {{ Route::is('admin.products.*') ? 'sidebar-active text-white' : '' }}">
                <span class="text-lg">🛍️</span> CRUD Produk
            </a>

            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition font-medium text-sm {{ Route::is('admin.orders.*') ? 'sidebar-active text-white' : '' }}">
                <span class="text-lg">📦</span> Kelola Pesanan
            </a>

            <a href="{{ route('admin.ai-settings') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition font-medium text-sm {{ Route::is('admin.ai-settings') ? 'sidebar-active text-white' : '' }}">
                <span class="text-lg">⚙️</span> Kelola API & Dataset
            </a>

            <a href="{{ route('admin.chat') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition font-medium text-sm {{ Route::is('admin.chat') ? 'sidebar-active text-white' : '' }}">
                <span class="text-lg">🤖</span> Chatbot Admin
            </a>
        </nav>

        <!-- Sidebar Footer / Profile -->
        <div class="border-t border-gray-800 pt-6 mt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@am.com' }}</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-gray-800 hover:bg-red-900/50 hover:text-red-200 transition py-2.5 px-4 rounded-xl text-xs font-semibold flex items-center justify-center gap-2">
                    🚪 Keluar (Logout)
                </button>
            </form>

            <a href="{{ route('home') }}" class="block w-full text-center mt-3 text-xs text-purple-400 hover:underline">
                View Live Storefront
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
        <!-- Main Top Header (hidden on mobile, but nice breadcrumbs for desktop) -->
        <header class="hidden md:flex justify-between items-center bg-white border-b border-gray-200 px-8 py-5">
            <div class="flex items-center gap-2 text-sm text-gray-400 font-medium">
                <span>Dashboard</span>
                <span>/</span>
                <span class="text-gray-800 font-bold">@yield('page_title', 'Home')</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-purple-100 text-purple-700 font-bold px-3 py-1 rounded-full uppercase">Standard License</span>
            </div>
        </header>

        <!-- Main Body -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
            <!-- Toast Notifications -->
            @if (session('success'))
                <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center gap-3 text-sm animate-fade-in shadow-sm">
                    <span class="text-lg">✅</span>
                    <div>
                        <strong>Berhasil:</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-4xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl flex items-center gap-3 text-sm animate-fade-in shadow-sm">
                    <span class="text-lg">⚠️</span>
                    <div>
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('fixed');
            sidebar.classList.toggle('inset-0');
        }
    </script>
<!-- Footer Complaint -->
<!-- Complaint form moved to landing page -->
<!-- Toast for copy notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
  <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Pesan berhasil disalin</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin-scripts.js') }}"></script>
</body>
</html>
