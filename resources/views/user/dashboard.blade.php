<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - AM Merchandise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <a href="{{ route('home') }}" class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    AM Merchandise Store
                </a>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden sm:inline text-sm text-gray-500 font-medium">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-4 py-2 rounded-xl text-xs transition duration-200">
                        🚪 Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-8">
        
        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center gap-3 text-sm shadow-sm max-w-3xl">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl flex items-center gap-3 text-sm shadow-sm max-w-3xl">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Panel: User Profile Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6 self-start">
                <div class="text-center space-y-3">
                    <!-- User Initial Avatar -->
                    <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-extrabold mx-auto shadow-md shadow-purple-500/10">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tipe Akun:</span>
                        <span class="font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full text-xs">Pelanggan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Bergabung Sejak:</span>
                        <span class="font-medium text-gray-700">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('home') }}" class="block w-full text-center bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-md shadow-purple-500/10 active:scale-98">
                        🛍️ Mulai Belanja
                    </a>
                </div>
            </div>

            <!-- Main Panel: Order History & Tickets -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Transaction History Card -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">📦 Riwayat Pembelian Anda</h3>
                        <p class="text-xs text-gray-500 mt-1">Daftar transaksi pesanan yang telah atau sedang diproses.</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">ID Pesanan</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                                @forelse($mockOrders as $order)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900 text-xs">{{ $order['id'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order['date'] }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800 max-w-[200px] truncate" title="{{ $order['product'] }}">
                                                {{ $order['product'] }}
                                            </div>
                                            @if(!empty($order['tracking']))
                                                <div class="text-xs text-purple-600 mt-1">Resi: <span class="font-mono bg-purple-50 px-1 py-0.5 rounded">{{ $order['tracking'] }}</span></div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $order['total'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($order['status'] === 'Sedang Dikirim')
                                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                                                    🚚 Sedang Dikirim
                                                </span>
                                            @else
                                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
                                                    ✅ Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <p class="font-medium">Belum ada transaksi.</p>
                                            <a href="{{ route('home') }}" class="text-xs text-purple-600 font-bold hover:underline mt-2 inline-block">Belanja produk pertamamu →</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Help Support Mock Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        💬 <span>Butuh Bantuan?</span>
                    </h3>
                    <p class="text-sm text-gray-500">
                        Mengalami kendala terkait ukuran pakaian, estimasi pengiriman, atau ingin komplain cacat produksi? Jangan ragu untuk berdiskusi dengan asisten AI pintar kami yang aktif 24 jam penuh di halaman beranda.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-purple-600 hover:text-purple-700 transition">
                            Tanya Asisten AI Toko Sekarang →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
            <p>&copy; 2026 AM Merchandise Store. Semua hak dilindungi.</p>
        </div>
    </footer>

</body>
</html>
