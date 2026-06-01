<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil - AM Merchandise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes checkPop {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-scaleIn {
            animation: scaleIn 0.4s ease-out;
        }
        .animate-check {
            animation: checkPop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen flex flex-col">

    <!-- MAIN CONTAINER -->
    <main class="flex-grow flex items-center justify-center p-4 md:p-8 animate-scaleIn">
        <div class="max-w-2xl w-full bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-purple-100 flex flex-col">
            
            <!-- TOP BANNER: CELEBRATION -->
            <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-8 text-center text-white relative">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white via-indigo-900 to-purple-900"></div>
                
                <!-- Animated Checklist Icon -->
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-white border-opacity-35 animate-check">
                    <span class="text-3xl">🎉</span>
                </div>
                
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Pemesanan Berhasil!</h1>
                <p class="text-purple-100 text-xs md:text-sm mt-1 border-t border-purple-500 border-opacity-30 pt-2 inline-block">Terima kasih atas kepercayaan Anda berbelanja di AM Merchandise</p>
            </div>

            <!-- RECEIPT BODY (Dynamic Data from SessionStorage) -->
            <div class="p-6 md:p-8 space-y-6 flex-grow" id="receipt-content">
                
                <!-- Receipt Loading State -->
                <div id="loading-state" class="text-center py-12 text-gray-500 text-sm">
                    Memuat tanda terima pemesanan...
                </div>

                <!-- Receipt Detail State -->
                <div id="receipt-detail" class="hidden space-y-6">
                    
                    <!-- Invoice Info -->
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 text-xs md:text-sm text-gray-500">
                        <div>
                            <p class="font-bold text-gray-800" id="invoice-no">INV/2026/000000</p>
                            <p class="text-[10px] text-gray-400 mt-0.5" id="order-date">1 Juni 2026</p>
                        </div>
                        <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-[10px] md:text-xs uppercase tracking-wider flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Sukses
                        </span>
                    </div>

                    <!-- Product Bought Card -->
                    <div class="flex gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <img id="product-img" src="" alt="Produk" class="w-20 h-20 object-cover rounded-xl border border-gray-200 bg-white flex-shrink-0">
                        <div class="min-w-0 flex-1 flex flex-col justify-center">
                            <h3 id="product-title" class="font-bold text-sm text-gray-900 leading-snug truncate">Nama Produk</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Ukuran: <strong id="product-size" class="text-gray-700">M</strong></p>
                            <p class="text-xs text-gray-400">Jumlah: <strong id="product-qty" class="text-gray-700">1 pcs</strong></p>
                        </div>
                    </div>

                    <!-- Invoice Cost Breakdown -->
                    <div class="space-y-2 border-b border-gray-100 pb-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Detail Biaya</h4>
                        <div class="flex justify-between text-xs md:text-sm text-gray-500">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-gray-800" id="cost-subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm text-gray-500">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium text-gray-800" id="cost-shipping">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm md:text-base text-gray-900 font-bold border-t border-dashed border-gray-200 pt-3">
                            <span>Total Pembayaran</span>
                            <span class="text-lg font-extrabold text-indigo-600" id="cost-total">Rp 0</span>
                        </div>
                    </div>

                    <!-- Customer Detail Breakdown -->
                    <div class="bg-purple-50 bg-opacity-40 p-5 rounded-2xl border border-purple-100 space-y-4 text-xs md:text-sm">
                        <h4 class="text-xs font-bold text-purple-600 uppercase tracking-wider border-b border-purple-100 pb-2">🚚 Informasi Pengiriman & Pembayaran</h4>
                        
                        <div class="grid grid-cols-3 gap-y-3 text-gray-600">
                            <span class="font-semibold text-gray-400">Nama Penerima</span>
                            <span class="col-span-2 text-gray-800 font-medium" id="customer-name">-</span>

                            <span class="font-semibold text-gray-400">No. WhatsApp</span>
                            <span class="col-span-2 text-gray-800 font-medium" id="customer-phone">-</span>

                            <span class="font-semibold text-gray-400">Alamat Lengkap</span>
                            <span class="col-span-2 text-gray-800 leading-relaxed font-medium" id="customer-address">-</span>

                            <span class="font-semibold text-gray-400">Ekspedisi Kurir</span>
                            <span class="col-span-2 text-gray-800 font-medium" id="customer-courier">-</span>

                            <span class="font-semibold text-gray-400">Metode Bayar</span>
                            <span class="col-span-2 text-gray-800 font-medium" id="customer-payment">-</span>

                            <span class="font-semibold text-gray-400">Catatan</span>
                            <span class="col-span-2 text-gray-800 italic font-medium" id="customer-notes">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTON ACTIONS -->
            <div class="p-6 md:p-8 bg-gray-50 border-t border-gray-100 flex flex-col gap-3">
                <button onclick="confirmOrderWhatsApp()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-center py-4 rounded-2xl font-bold text-lg hover:shadow-lg transition-all hover:-translate-y-0.5 shadow-md flex items-center justify-center gap-2">
                    💬 Konfirmasi Pemesanan via WhatsApp
                </button>
                <a href="/" class="w-full bg-white border border-gray-200 text-gray-700 text-center py-3 rounded-xl font-bold text-sm hover:bg-gray-100 transition-all block">
                    🛍️ Kembali Belanja ke Toko
                </a>
            </div>
        </div>
    </main>

    <!-- CLIENT RECEIPT LOADER & WHATSAPP REDIRECT -->
    <script>
        let order = null;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const rawData = sessionStorage.getItem('latest_order');
            
            if (!rawData) {
                // Jika tidak ada data order, alihkan kembali ke halaman utama
                window.location.href = '/';
                return;
            }

            order = JSON.parse(rawData);

            // Sembunyikan loading state & tampilkan struk
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('receipt-detail').classList.remove('hidden');

            // Populate data struk
            document.getElementById('invoice-no').innerText = order.invoiceNo;
            document.getElementById('order-date').innerText = order.date;
            document.getElementById('product-img').src = order.productImage;
            document.getElementById('product-title').innerText = order.productName;
            document.getElementById('product-size').innerText = order.size;
            document.getElementById('product-qty').innerText = order.qty + ' pcs';
            
            document.getElementById('cost-subtotal').innerText = formatRupiah(order.price * order.qty);
            document.getElementById('cost-shipping').innerText = formatRupiah(order.shipping);
            document.getElementById('cost-total').innerText = formatRupiah(order.total);

            // Populate data pelanggan
            document.getElementById('customer-name').innerText = order.customer.name;
            document.getElementById('customer-phone').innerText = order.customer.phone;
            document.getElementById('customer-address').innerText = order.customer.address;
            document.getElementById('customer-courier').innerText = order.customer.courier;
            document.getElementById('customer-payment').innerText = order.customer.payment;
            document.getElementById('customer-notes').innerText = order.customer.notes;
        });

        function confirmOrderWhatsApp() {
            if (!order) return;

            const message = `Halo AM Merchandise! Saya ingin melakukan konfirmasi pemesanan:

🧾 *Invoice:* ${order.invoiceNo}
📅 *Tanggal:* ${order.date}

👤 *Detail Pelanggan:*
• Nama: ${order.customer.name}
• WhatsApp: ${order.customer.phone}
• Alamat: ${order.customer.address}

🛍️ *Pesanan:*
• Produk: ${order.productName}
• Ukuran: ${order.size}
• Jumlah: ${order.qty} pcs
• Kurir: ${order.customer.courier}
• Pembayaran: ${order.customer.payment}
• Catatan: ${order.customer.notes}

💰 *Rincian Biaya:*
• Subtotal: ${formatRupiah(order.price * order.qty)}
• Ongkos Kirim: ${formatRupiah(order.shipping)}
• *Total Tagihan:* *${formatRupiah(order.total)}*

Mohon segera diproses ya. Terima kasih!`;

            const encodedMsg = encodeURIComponent(message);
            const waUrl = `https://wa.me/6281234567890?text=${encodedMsg}`;
            
            window.open(waUrl, '_blank');
        }
    </script>
</body>
</html>
