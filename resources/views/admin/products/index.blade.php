@extends('admin.layout')

@section('title', 'CRUD Produk')
@section('page_title', 'Kelola Produk')

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden animate-fade-in">
    <!-- Header CRUD -->
    <div class="px-6 py-5 md:px-8 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Produk Toko</h3>
            <p class="text-xs text-gray-500 mt-1">Total terdapat {{ $products->count() }} produk terdaftar</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" id="productSearch" placeholder="Cari produk..." class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition">
            <a href="{{ route('admin.products.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md shadow-purple-500/20 active:scale-95">
                ➕ Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse product-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4 md:px-8">Produk</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4">Badge</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition">
                        <!-- Image and Name -->
                        <td class="px-6 py-4 md:px-8 flex items-center gap-3">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                            <div class="min-w-0">
                                <span class="block font-semibold text-gray-800 truncate max-w-[250px]" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </span>
                                <span class="block text-xs text-gray-400 mt-0.5 font-mono">slug: {{ $product->slug }}</span>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600">
                                {{ $product->category }}
                            </span>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $product->price }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Raw: {{ number_format($product->price_raw, 0, ',', '.') }}</div>
                        </td>

                        <!-- Stock -->
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->stock > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $product->stock }} pcs
                            </span>
                        </td>

                        <!-- Badge -->
                        <td class="px-6 py-4">
                            @if($product->badge)
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-55 bg-purple-100 text-purple-700">
                                    {{ $product->badge }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition" title="Edit Produk">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition" title="Hapus Produk">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-3xl mb-2">📦</div>
                            <p class="font-medium text-sm">Belum ada produk terdaftar.</p>
                            <a href="{{ route('admin.products.create') }}" class="text-xs text-purple-600 font-bold hover:underline mt-2 inline-block">
                                Tambah Produk Pertama Anda
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
