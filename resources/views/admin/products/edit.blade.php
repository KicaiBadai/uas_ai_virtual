@extends('admin.layout')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8 animate-fade-in">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800">Ubah Produk: {{ $product->name }}</h3>
        <p class="text-xs text-gray-500 mt-1">Perbarui informasi detail produk di bawah ini</p>
    </div>

    <!-- Errors Alert -->
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-sm border border-red-200">
            <span class="font-bold">⚠️ Mohon perbaiki error berikut:</span>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Produk</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $product->name) }}" 
                    required 
                    placeholder="Contoh: Hoodie Anime One Piece Nika"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
                <select 
                    name="category" 
                    id="category" 
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 bg-white"
                >
                    <option value="Hoodie" {{ old('category', $product->category) === 'Hoodie' ? 'selected' : '' }}>Hoodie</option>
                    <option value="Kaos" {{ old('category', $product->category) === 'Kaos' ? 'selected' : '' }}>Kaos</option>
                    <option value="Topi" {{ old('category', $product->category) === 'Topi' ? 'selected' : '' }}>Topi</option>
                </select>
            </div>

            <!-- Badge -->
            <div>
                <label for="badge" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Badge Label (Opsional)</label>
                <input 
                    type="text" 
                    name="badge" 
                    id="badge" 
                    value="{{ old('badge', $product->badge) }}" 
                    placeholder="Contoh: Bestseller, New, Hot Item"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
            </div>

            <!-- Price Visual -->
            <div>
                <label for="price" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Harga Tampilan (Visual)</label>
                <input 
                    type="text" 
                    name="price" 
                    id="price" 
                    value="{{ old('price', $product->price) }}" 
                    required 
                    placeholder="Contoh: Rp 185.000"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
            </div>

            <!-- Price Raw -->
            <div>
                <label for="price_raw" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Harga Angka (Raw Integer)</label>
                <input 
                    type="number" 
                    name="price_raw" 
                    id="price_raw" 
                    value="{{ old('price_raw', $product->price_raw) }}" 
                    required 
                    placeholder="Contoh: 185000"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Stok Produk</label>
                <input 
                    type="number" 
                    name="stock" 
                    id="stock" 
                    value="{{ old('stock', $product->stock) }}" 
                    required 
                    min="0"
                    placeholder="Contoh: 15"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
            </div>

            <!-- Image URL -->
            <div class="sm:col-span-2">
                <label for="image" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">URL Gambar Produk</label>
                <input 
                    type="url" 
                    name="image" 
                    id="image" 
                    value="{{ old('image', $product->image) }}" 
                    required 
                    placeholder="Contoh: https://i.pinimg.com/... atau link gambar valid"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >
                <div class="mt-2 flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <span class="text-xs text-gray-400">Preview:</span>
                    <img src="{{ $product->image }}" alt="Preview" class="w-16 h-16 rounded-lg object-cover border border-gray-200 bg-white">
                </div>
            </div>

            <!-- Sizes Checklist -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ukuran yang Tersedia</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    @php
                        $productSizes = is_array($product->sizes) ? $product->sizes : [];
                        $oldSizes = old('sizes');
                    @endphp
                    @foreach(['S', 'M', 'L', 'XL', 'XXL', 'All Size (Adjustable)', 'Medium (58cm)'] as $sz)
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="sizes[]" 
                                value="{{ $sz }}"
                                {{ (is_array($oldSizes) && in_array($sz, $oldSizes)) || (!is_array($oldSizes) && in_array($sz, $productSizes)) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500/30"
                            >
                            <span>{{ $sz }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div class="sm:col-span-2">
                <label for="description" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Produk</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    placeholder="Tulis deskripsi detail bahan, sablon, dan kelebihan produk..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800"
                >{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Features -->
            <div class="sm:col-span-2 space-y-3">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Fitur / Kelebihan Produk (Maks 5 Fitur)</label>
                <div class="space-y-2">
                    @php
                        $productFeatures = is_array($product->features) ? $product->features : [];
                    @endphp
                    @for($i = 0; $i < 5; $i++)
                        @php
                            $featureValue = old('features.'.$i, isset($productFeatures[$i]) ? $productFeatures[$i] : '');
                        @endphp
                        <input 
                            type="text" 
                            name="features[]" 
                            value="{{ $featureValue }}" 
                            placeholder="Fitur {{ $i + 1 }} (Contoh: Bahan Cotton Fleece 280gsm)"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-xs text-gray-800"
                        >
                    @endfor
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition font-semibold text-sm">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-semibold text-sm transition shadow-md shadow-purple-500/10">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
