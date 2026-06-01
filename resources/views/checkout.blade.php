<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $product['name'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text">✨ AM Merchandise</span>
            </a>
            <a href="/product/{{ $product['slug'] }}" class="text-sm font-semibold text-purple-600 hover:text-indigo-800 transition flex items-center gap-1">
                &larr; Kembali ke Produk
            </a>
        </div>
    </header>

    <main class="flex-grow max-w-6xl mx-auto px-4 py-8 md:py-12 w-full">
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">📦 Formulir Pemesanan</h1>
            <p class="text-gray-500 text-sm md:text-base mt-1">Satu langkah lagi untuk mendapatkan merchandise impianmu.</p>
        </div>

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT: PRODUCT SUMMARY -->
            <div class="lg:col-span-5 bg-white rounded-3xl shadow-lg border border-gray-100 p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">🛍️ Ringkasan Pesanan</h2>
                
                <div class="flex gap-4">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-24 h-24 object-cover rounded-2xl border border-gray-100 shadow-sm flex-shrink-0">
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] bg-purple-100 text-purple-600 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $product['category'] }}</span>
                        <h3 class="font-bold text-sm md:text-base text-gray-900 mt-1 leading-snug line-clamp-2">{{ $product['name'] }}</h3>
                        <p class="text-purple-600 font-extrabold text-base mt-1" id="product-base-price">{{ $product['price'] }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <!-- Size Selector -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Pilih Ukuran</label>
                        <div class="flex gap-2">
                            @foreach($product['sizes'] as $index => $size)
                            <label class="cursor-pointer">
                                <input type="radio" name="selected_size" value="{{ $size }}" class="peer sr-only" {{ $index === 0 ? 'checked' : '' }}>
                                <span class="border border-gray-200 text-gray-700 font-bold px-4 py-2 rounded-lg text-sm transition-all bg-white hover:border-indigo-500 hover:text-indigo-500 flex items-center justify-center min-w-[45px] peer-checked:border-indigo-600 peer-checked:text-indigo-600 peer-checked:bg-indigo-50/50 peer-checked:ring-2 peer-checked:ring-indigo-600/20">
                                    {{ $size }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity Selector -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Jumlah (Pcs)</label>
                        <div class="flex items-center gap-3">
                            <button onclick="decrementQty()" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-lg text-gray-600 hover:bg-gray-200 transition">-</button>
                            <input type="number" id="quantity" value="1" min="1" class="w-16 h-10 border border-gray-200 rounded-lg text-center font-bold text-gray-800 input-focus transition-all" readonly>
                            <button onclick="incrementQty()" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-lg text-gray-600 hover:bg-gray-200 transition">+</button>
                        </div>
                    </div>
                </div>

                <!-- Price Receipt Details -->
                <div class="border-t border-gray-100 pt-6 space-y-3">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Harga Satuan</span>
                        <span class="font-medium text-gray-800">{{ $product['price'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-medium text-gray-800" id="subtotal-product">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Ongkos Kirim Estimasi</span>
                        <span class="font-medium text-gray-800" id="shipping-cost">Rp 15.000</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                        <span class="text-xl font-extrabold text-indigo-600" id="total-price">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: CUSTOMER SHIPPING FORM -->
            <form id="checkout-form" class="lg:col-span-7 bg-white rounded-3xl shadow-lg border border-gray-100 p-6 md:p-8 space-y-6" onsubmit="handleCheckout(event)">
                <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">🚚 Informasi Pengiriman</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" id="customer_name" required placeholder="Contoh: Akbar Wiratama" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 input-focus transition-all">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">No. WhatsApp Aktif</label>
                        <input type="tel" id="customer_phone" required placeholder="Contoh: 08123456789" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 input-focus transition-all">
                    </div>
                </div>

                <div>
                    <label for="shipping_address" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Lengkap Pengiriman</label>
                    <textarea id="shipping_address" required rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 input-focus transition-all resize-none"></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="courier" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ekspedisi Kurir</label>
                        <select id="courier" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 input-focus transition-all bg-white" onchange="updateShippingCost()">
                            <option value="JNE">JNE (Reguler - Estimasi 2-3 Hari)</option>
                            <option value="J&T">J&T Express (Kilat - Estimasi 1-2 Hari)</option>
                            <option value="Sicepat">Sicepat (Hemat - Estimasi 3-4 Hari)</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_method" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Metode Pembayaran (Simulasi)</label>
                        <select id="payment_method" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 input-focus transition-all bg-white">
                            <option value="Transfer Bank BCA">Transfer Bank (BCA Virtual Account)</option>
                            <option value="Transfer Bank Mandiri">Transfer Bank (Mandiri)</option>
                            <option value="GoPay / OVO">E-Wallet (GoPay / ShopeePay / OVO)</option>
                            <option value="COD">Bayar di Tempat (COD)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                    <input type="text" id="notes" placeholder="Contoh: Warna cadangan, taruh di pos satpam, dll." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 input-focus transition-all">
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-center py-4 rounded-2xl font-bold text-lg hover:shadow-lg transition-all hover-lift shadow-md block">
                        💳 Selesaikan Pembayaran & Pesan
                    </button>
                    <p class="text-xs text-center text-gray-400 mt-2.5">Simulasi pemesanan visual 100% aman tanpa database</p>
                </div>
            </form>
        </div>
    </main>

    <footer class="py-8 px-4 text-center text-gray-400 text-xs border-t border-gray-100 bg-white">
        <p>&copy; 2024 AM Merchandise. Semua hak dilindungi. visual-only checkout demonstration.</p>
    </footer>

    <!-- CLIENT LOGIC -->
    <script>
        const basePrice = {{ $product['price_raw'] }};
        const productName = "{{ $product['name'] }}";
        const productImage = "{{ $product['image'] }}";
        const productSlug = "{{ $product['slug'] }}";

        let qty = 1;
        let shippingCost = 15000;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function updatePrice() {
            const subtotalProd = basePrice * qty;
            const total = subtotalProd + shippingCost;

            document.getElementById('subtotal-product').innerText = formatRupiah(subtotalProd);
            document.getElementById('shipping-cost').innerText = formatRupiah(shippingCost);
            document.getElementById('total-price').innerText = formatRupiah(total);
        }

        function incrementQty() {
            qty++;
            document.getElementById('quantity').value = qty;
            updatePrice();
        }

        function decrementQty() {
            if (qty > 1) {
                qty--;
                document.getElementById('quantity').value = qty;
                updatePrice();
            }
        }

        function updateShippingCost() {
            const courier = document.getElementById('courier').value;
            if (courier === 'JNE') {
                shippingCost = 15000;
            } else if (courier === 'J&T') {
                shippingCost = 22000;
            } else if (courier === 'Sicepat') {
                shippingCost = 10000;
            }
            updatePrice();
        }

        function handleCheckout(e) {
            e.preventDefault();

            // Ambil ukuran terpilih
            const selectedSizeElement = document.querySelector('input[name="selected_size"]:checked');
            const selectedSize = selectedSizeElement ? selectedSizeElement.value : 'M';

            // Ambil data pelanggan
            const name = document.getElementById('customer_name').value.trim();
            const phone = document.getElementById('customer_phone').value.trim();
            const address = document.getElementById('shipping_address').value.trim();
            const courier = document.getElementById('courier').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const notes = document.getElementById('notes').value.trim();

            const invoiceNo = 'INV/' + new Date().getFullYear() + '/' + Math.floor(100000 + Math.random() * 900000);

            // Simpan objek pesanan ke sessionStorage
            const orderData = {
                invoiceNo: invoiceNo,
                date: new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }),
                productName: productName,
                productImage: productImage,
                productSlug: productSlug,
                price: basePrice,
                qty: qty,
                size: selectedSize,
                shipping: shippingCost,
                total: (basePrice * qty) + shippingCost,
                customer: {
                    name: name,
                    phone: phone,
                    address: address,
                    courier: courier,
                    payment: paymentMethod,
                    notes: notes || '-'
                }
            };

            sessionStorage.setItem('latest_order', JSON.stringify(orderData));

            // Alihkan ke halaman sukses order
            window.location.href = '/checkout/success';
        }

        // Initialize Price Calculation
        document.addEventListener('DOMContentLoaded', () => {
            updatePrice();
        });
    </script>
</body>
</html>
