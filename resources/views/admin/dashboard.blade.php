@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Welcome Panel -->
    <div class="bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-900 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-15">
            <div class="absolute -top-10 -right-10 w-60 h-60 bg-white rounded-full filter blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-400 rounded-full filter blur-2xl"></div>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-2">Halo, Selamat Datang Kembali! 👋</h2>
            <p class="text-purple-200 text-sm md:text-base max-w-2xl">
                Kelola inventaris produk Anda, ganti API model kecerdasan buatan (AI), dan sesuaikan dataset toko secara dinamis melalui dashboard admin AM Merchandise.
            </p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stat Item 1 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Produk</span>
                <span class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</span>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 text-xl font-bold">
                🛍️
            </div>
        </div>

        <!-- Stat Item 2 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kategori Aktif</span>
                <span class="text-3xl font-bold text-gray-800">{{ $stats['categories'] }}</span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-xl font-bold">
                🏷️
            </div>
        </div>

        <!-- Stat Item 3: Total Orders -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pesanan</span>
                <span class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-xl font-bold">
                📦
            </div>
        </div>

        <!-- Stat Item 4: Pending Orders -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pesanan Pending</span>
                <span class="text-3xl font-bold text-amber-600">{{ $stats['pending_orders'] }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 text-xl font-bold">
                ⏳
            </div>
        </div>

        <!-- Stat Item 5: Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Omset</span>
                <span class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-xl font-bold">
                💰
            </div>
        </div>

        <!-- Stat Item 6: Model AI -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Model AI Aktif</span>
                <span class="text-sm font-bold text-gray-700 uppercase truncate max-w-[150px] block" title="{{ $stats['api_model'] }}">
                    {{ $stats['api_model'] }}
                </span>
            </div>
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-600 text-xl font-bold">
                🤖
            </div>
        </div>
    </div>

    <!-- Features Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- DB Status -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                📂 <span>Status Database & Koneksi</span>
            </h3>
            <p class="text-sm text-gray-500">
                Aplikasi Anda saat ini terhubung ke database **MySQL** melalui XAMPP. Seluruh data produk dan pengaturan AI kini dinamis dibaca dari database.
            </p>
            <div class="bg-gray-50 p-4 rounded-2xl text-xs space-y-2 border border-gray-100">
                <div class="flex justify-between">
                    <span class="text-gray-400">Database Driver:</span>
                    <span class="font-bold text-gray-700">MySQL (PDO)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Database Name:</span>
                    <span class="font-bold text-gray-700">uas_ai_virtual</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Host / Port:</span>
                    <span class="font-bold text-gray-700">127.0.0.1 : 3306</span>
                </div>
            </div>
            <div class="pt-2">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 hover:text-purple-700 transition">
                    Kelola Produk Sekarang →
                </a>
            </div>
        </div>

        <!-- AI Assistant Status -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                🤖 <span>Asisten Kecerdasan Buatan (AI)</span>
            </h3>
            <p class="text-sm text-gray-500">
                Asisten AI di toko Anda terintegrasi dengan Mistral AI. AI membaca knowledge base berupa daftar produk dinamis langsung dari database, ditambah dengan custom Dataset yang Anda input.
            </p>
            
            <div class="bg-gray-50 p-4 rounded-2xl text-xs space-y-2 border border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Input Dataset Kustom:</span>
                    <span class="font-semibold text-emerald-600">Aktif & Terhubung</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Dynamic Knowledge Base:</span>
                    <span class="font-semibold text-purple-600">Sinkron Otomatis ({{ $stats['total_products'] }} Produk)</span>
                </div>
            </div>
            
            <div class="pt-2">
                <a href="{{ route('admin.ai-settings') }}" class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 hover:text-purple-700 transition">
                    Kelola AI & Input Dataset →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
