@extends('admin.layout')

@section('title', 'Kelola Pesanan')
@section('page_title', 'Daftar Pesanan Customer')

@section('content')
<div class="space-y-6">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pesanan</span>
                <span class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</span>
            </div>
            <span class="text-xl bg-purple-50 p-3 rounded-xl">📦</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pesanan Diproses / Pending</span>
                <span class="text-2xl font-bold text-gray-800">
                    {{ $orders->whereIn('status', ['pending', 'diproses'])->count() }}
                </span>
            </div>
            <span class="text-xl bg-amber-50 p-3 rounded-xl">⏳</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Penjualan</span>
                <span class="text-2xl font-bold text-emerald-600">
                    Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}
                </span>
            </div>
            <span class="text-xl bg-emerald-50 p-3 rounded-xl">💰</span>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="mb-4"><input type="text" id="order-search" placeholder="Cari pesanan..." class="w-full border rounded px-3 py-2"></div>
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pesanan Masuk</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar lengkap transaksi pembelian customer.</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <span class="text-4xl block mb-2">📥</span>
                <p class="font-semibold text-sm">Belum ada pesanan yang masuk.</p>
                <p class="text-xs text-gray-400 mt-1">Pesanan yang dibuat oleh customer di checkout akan tampil di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-4">No. Resi / Invoice</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">No. WhatsApp</th>
                            <th class="px-6 py-4">Produk / Item</th>
                            <th class="px-6 py-4 text-center">Jumlah & Ukuran</th>
                            <th class="px-6 py-4 text-right">Total Tagihan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($orders as $order)
                            @php
                                // Normalize phone number to international format 62xxx
                                $cleanPhone = preg_replace('/\D/', '', $order->customer_whatsapp);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }

                                // Construct WA text
                                $waMessage = "Halo " . $order->customer_name . "\n\n"
                                           . "Pesanan Anda berhasil diproses.\n\n"
                                           . "Nomor Resi: " . $order->receipt_number . "\n"
                                           . "Produk: " . ($order->product->name ?? 'Merchandise') . "\n"
                                           . "Status: " . ucfirst($order->status) . "\n\n"
                                           . "Terima kasih telah berbelanja di AM Merchandise.";
                                $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($waMessage);
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <!-- Resi/Invoice -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-extrabold text-indigo-600 block text-xs tracking-wider">{{ $order->receipt_number }}</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</span>
                                </td>

                                <!-- Pelanggan -->
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-800 block text-xs truncate max-w-[150px]" title="{{ $order->customer_name }}">
                                        {{ $order->customer_name }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 block mt-0.5 truncate max-w-[150px]" title="{{ $order->customer_address }}">
                                        📍 {{ $order->customer_address }}
                                    </span>
                                </td>

                                <!-- No. WhatsApp -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-semibold text-xs transition-colors"
                                       title="Buka WhatsApp {{ $order->customer_whatsapp }}">
                                        <span>📱</span>
                                        <span>{{ $order->customer_whatsapp }}</span>
                                    </a>
                                </td>

                                <!-- Produk -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($order->product && $order->product->image)
                                            <img src="{{ $order->product->image }}" alt="" class="w-8 h-8 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                        @endif
                                        <div class="min-w-0">
                                            <span class="font-semibold text-gray-800 block text-xs truncate max-w-[180px]" title="{{ $order->product->name ?? 'Produk dihapus' }}">
                                                {{ $order->product->name ?? 'Produk dihapus' }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 block mt-0.5 uppercase tracking-wider font-bold">
                                                {{ $order->courier }} ({{ $order->payment_method }})
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jumlah & Ukuran -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                        {{ $order->size }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium ml-1">x {{ $order->quantity }}</span>
                                </td>

                                <!-- Total Tagihan -->
                                <td class="px-6 py-4 whitespace-nowrap text-right font-extrabold text-gray-800 text-xs">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($order->status === 'pending')
                                        <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Pending</span>
                                    @elseif($order->status === 'diproses')
                                        <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Diproses</span>
                                    @elseif($order->status === 'dikirim')
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Dikirim</span>
                                    @elseif($order->status === 'selesai')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Selesai</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Status Form -->
                                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="text-xs bg-white border border-gray-200 rounded-lg px-2 py-1.5 font-medium text-gray-600 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="dikirim" {{ $order->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>

                                        <!-- WhatsApp Send Resi -->
                                        <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition-all hover:shadow-md">
                                            <span>💬</span> Kirim WhatsApp
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
