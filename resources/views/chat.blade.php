<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchandise Store</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #d1d5db #f3f4f6;
        }

        *::-webkit-scrollbar {
            width: 6px;
        }

        *::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        *::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slideInUp {
            animation: slideInUp 0.5s ease-out;
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-scaleIn {
            animation: scaleIn 0.3s ease-out;
        }

        .animate-slideInRight {
            animation: slideInRight 0.3s ease-out;
        }

        .pulse-dot {
            animation: bounce 2s infinite;
        }

        .chat-message {
            animation: slideInUp 0.3s ease-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }

        .message-bubble {
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.6;
            font-size: 14px;
        }

        /* Markdown Styling */
        .message-bubble strong {
            font-weight: 700;
            color: #4f46e5;
        }

        .message-bubble ul {
            list-style-type: disc;
            margin-left: 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .message-bubble ol {
            list-style-type: decimal;
            margin-left: 1.25rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .message-bubble li {
            margin-bottom: 0.25rem;
        }

        .message-bubble p:not(:last-child) {
            margin-bottom: 0.75rem;
        }

        .message-bubble code {
            background: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.9em;
        }

        .message-bubble a {
            color: #4f46e5;
            text-decoration: underline;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .message-bubble a:hover {
            color: #6366f1;
        }

        .message-bubble img {
            max-width: 180px;
            height: auto;
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            display: block;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .message-bubble img:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            cursor: zoom-in;
        }


        @keyframes highlightFlash {
            0%, 100% {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 2px solid transparent;
            }
            50% {
                box-shadow: 0 0 25px 5px rgba(139, 92, 246, 0.5); /* purple glow */
                border: 2px solid rgb(139, 92, 246);
                transform: scale(1.02);
            }
        }

        .highlight-product {
            animation: highlightFlash 1.5s ease-out;
            transition: all 0.5s ease;
        }



        .loading-dots {
            display: inline-block;
        }

        .loading-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            animation: bounce 1.4s infinite ease-in-out;
            margin: 0 2px;
        }

        .loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        /* Responsive Chat */
        @media (max-width: 768px) {
            #chat-container {
                width: calc(100vw - 1rem) !important;
                height: calc(100vh - 7rem) !important;
                max-height: calc(100vh - 7rem) !important;
                right: 0.5rem !important;
                bottom: 5.5rem !important;
                border-radius: 1.5rem !important;
                max-width: 500px;
            }

            .chat-header {
                padding: 0.65rem 0.75rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-shrink: 0;
                gap: 0.5rem;
            }

            .chat-header h2 {
                font-size: 0.8rem;
                margin: 0;
                line-height: 1.2;
            }

            .chat-header .text-purple-100 {
                font-size: 0.65rem !important;
                line-height: 1.1;
            }

            .close-btn {
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
                min-height: 28px !important;
                font-size: 16px !important;
                padding: 0 !important;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #chat-box {
                padding: 0.65rem;
                flex: 1;
                overflow-y: auto;
                min-height: 0;
            }

            .chat-input-area {
                padding: 0.5rem;
                flex-shrink: 0;
                border-top: 1px solid #e5e7eb;
            }

            .chat-input-area p {
                font-size: 0.65rem;
                display: none;
            }

            .message-bubble {
                font-size: 12px;
                max-width: 90% !important;
                padding: 0.5rem;
            }

            #message {
                padding: 0.5rem 0.75rem;
                font-size: 13px;
            }

            .chat-message {
                margin: 0.25rem 0;
            }
        }

        @media (max-width: 480px) {
            #chat-container {
                width: calc(100vw - 0.5rem) !important;
                height: calc(100vh - 6rem) !important;
                max-height: calc(100vh - 6rem) !important;
                right: 0.25rem !important;
                bottom: 5rem !important;
                border-radius: 0.875rem !important;
            }

            .chat-header {
                padding: 0.6rem 0.6rem;
                gap: 0.4rem;
            }

            .chat-header h2 {
                font-size: 0.75rem;
                line-height: 1.1;
            }

            .chat-header .text-purple-100 {
                font-size: 0.6rem !important;
                line-height: 1;
            }

            .close-btn {
                width: 26px !important;
                height: 26px !important;
                min-width: 26px !important;
                min-height: 26px !important;
                font-size: 15px !important;
            }

            #message {
                font-size: 12px;
            }

            .message-bubble {
                font-size: 11px;
                line-height: 1.4;
                padding: 0.4rem;
            }
        }

        /* Desktop */
        @media (min-width: 769px) {
            #chat-container {
                width: 420px !important;
                height: 600px !important;
                right: 1.5rem !important;
                bottom: 5rem !important;
            }

            .message-bubble {
                font-size: 14px;
            }
        }

        /* Smooth transitions */
        .chat-visible {
            display: flex !important;
        }

        .chat-hidden {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">

    <!-- HEADER -->
    <header class="bg-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold gradient-text">
                    AM Merchandise Store
                </h1>
                <p class="text-gray-500 text-xs md:text-sm">Koleksi fashion terbaik untuk gaya kamu</p>
            </div>

            <nav class="space-x-4 md:space-x-8 hidden md:block">
                <a href="/" class="text-gray-700 font-medium hover:text-purple-600 transition">Home</a>
                <a href="#produk" class="text-gray-700 font-medium hover:text-purple-600 transition">Produk</a>
                <a href="#produk" onclick="filterCategory('Hoodie')" class="text-gray-700 font-medium hover:text-purple-600 transition">Hoodie</a>
                <a href="#produk" onclick="filterCategory('Kaos')" class="text-gray-700 font-medium hover:text-purple-600 transition">Kaos</a>
                <a href="#produk" onclick="filterCategory('Topi')" class="text-gray-700 font-medium hover:text-purple-600 transition">Topi</a>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-900 text-white py-12 md:py-24 overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 md:w-80 h-40 md:h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 md:w-80 h-40 md:h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6 grid md:grid-cols-2 gap-6 md:gap-12 items-center relative z-10">
            <div class="animate-slideInUp">
                <h2 class="text-3xl md:text-6xl font-bold leading-tight mb-4 md:mb-6">
    Produk Unggulan AM Merchandise
</h2>
<p class="text-purple-100 text-base md:text-lg mb-6 md:mb-8 leading-relaxed">
    Temukan <strong>Hoodie Attack on Titan</strong> (Rp 185.000), <strong>Kaos Solo Leveling</strong> (Rp 95.000), dan <strong>Topi Streetwear</strong> (Rp 120.000) dengan desain eksklusif.
</p>
<a href="#produk" class="inline-block bg-white text-purple-700 px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-base md:text-lg hover:bg-gray-100 transition-all hover-lift shadow-lg w-full md:w-auto text-center">
    🛍️ Lihat Koleksi
</a>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-6 animate-fadeIn">
                <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 shadow-lg hover-lift">
                    <img src="https://i.pinimg.com/736x/87/d5/cd/87d5cd61e1312d7a25b3e03dbaca2b33.jpg" class="rounded-lg md:rounded-xl mb-2 md:mb-4 w-full h-24 md:h-40 object-cover">
                    <h3 class="font-bold text-sm md:text-lg text-gray-900">Hoodie Attack on Titan</h3>
                    <p class="text-purple-600 font-semibold text-sm md:text-base">Rp 185.000</p>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Desain ikonik anime</p>
                </div>

                <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 shadow-lg hover-lift">
                    <img src="https://i.pinimg.com/1200x/1e/03/8f/1e038f3a4b2b1f0a32dd0d965370fc88.jpg" class="rounded-lg md:rounded-xl mb-2 md:mb-4 w-full h-24 md:h-40 object-cover">
                    <h3 class="font-bold text-sm md:text-lg text-gray-900">Kaos Solo Leveling</h3>
                    <p class="text-purple-600 font-semibold text-sm md:text-base">Rp 95.000</p>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Motif karakter populer</p>
                </div>

                <div class="bg-white rounded-xl md:rounded-2xl p-3 md:p-5 shadow-lg hover-lift col-span-2">
                    <img src="https://i.pinimg.com/736x/c8/89/53/c889534db223ad0d72c1071b92fbc206.jpg" class="rounded-lg md:rounded-xl mb-2 md:mb-4 w-full h-24 md:h-40 object-cover">
                    <h3 class="font-bold text-sm md:text-lg text-gray-900">Topi Streetwear</h3>
                    <p class="text-purple-600 font-semibold text-sm md:text-base">Rp 120.000</p>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Gaya urbana modern</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUK -->
    <section id="produk" class="py-12 md:py-20 px-4 md:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-4">
                    🛍️ Semua Produk
                </h2>
                <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto">
                    Koleksi merchandise anime & streetwear pilihan terbaik kami
                </p>
            </div>

            <!-- Tabs Kategori -->
            <div class="flex justify-center gap-2 md:gap-4 mb-8 md:mb-12 flex-wrap">
                <button onclick="filterCategory('all')" id="tab-all"
                    class="tab-btn active-tab px-4 md:px-6 py-2 rounded-full font-semibold text-sm md:text-base transition-all">
                    Semua
                </button>
                <button onclick="filterCategory('Hoodie')" id="tab-Hoodie"
                    class="tab-btn px-4 md:px-6 py-2 rounded-full font-semibold text-sm md:text-base transition-all">
                    🧥 Hoodie
                </button>
                <button onclick="filterCategory('Kaos')" id="tab-Kaos"
                    class="tab-btn px-4 md:px-6 py-2 rounded-full font-semibold text-sm md:text-base transition-all">
                    👕 Kaos
                </button>
                <button onclick="filterCategory('Topi')" id="tab-Topi"
                    class="tab-btn px-4 md:px-6 py-2 rounded-full font-semibold text-sm md:text-base transition-all">
                    🧢 Topi
                </button>
            </div>

            <style>
                .tab-btn {
                    background: #f3f4f6;
                    color: #374151;
                }
                .tab-btn.active-tab {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                }
                .tab-btn:hover:not(.active-tab) {
                    background: #e5e7eb;
                }
                .product-card {
                    transition: opacity 0.3s ease, transform 0.3s ease;
                }
                .product-card.hidden-card {
                    display: none;
                }
            </style>

            <!-- Grid Produk -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6" id="product-grid">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-xl md:rounded-2xl overflow-hidden shadow-lg hover-lift group"
                     data-category="{{ $product['category'] }}"
                     id="produk-{{ $product['slug'] }}">
                    <div class="relative overflow-hidden h-36 md:h-52">
                        <img src="{{ $product['image'] }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                             alt="{{ $product['name'] }}"
                             loading="lazy">
                        @if(!empty($product['badge']))
                        <div class="absolute top-2 right-2 bg-purple-600 text-white px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ $product['badge'] }}
                        </div>
                        @endif
                        <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $product['category'] }}
                        </div>
                    </div>
                    <div class="p-2.5 md:p-4">
                        <h3 class="font-bold text-xs md:text-sm mb-1 text-gray-900 line-clamp-2 leading-tight">{{ $product['name'] }}</h3>
                        <div class="flex justify-between items-center mb-2 md:mb-3">
                            <p class="text-purple-600 font-bold text-sm md:text-base">{{ $product['price'] }}</p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $product['stock'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                Stok: {{ $product['stock'] }}
                            </span>
                        </div>
                        <a href="/product/{{ $product['slug'] }}"
                           class="block w-full text-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-1.5 md:py-2 rounded-lg font-semibold text-xs md:text-sm hover:shadow-lg transition-all">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FOOTER SECTION (untuk spacing) -->
    <section class="py-12 md:py-20 px-4 md:px-6 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">🎯 Tentang Kami</h3>
                    <p class="text-gray-400 text-sm">Toko merchandise fashion online terpercaya dengan produk berkualitas tinggi.</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">📦 Layanan</h3>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li>Gratis Ongkir</li>
                        <li>Garansi Kualitas</li>
                        <li>Support 24/7</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">🔗 Links</h3>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Beranda</a></li>
                        <li><a href="#" class="hover:text-white">Produk</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Login Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">📞 Hubungi</h3>
                    <p class="text-gray-400 text-sm">Email: info@merchandise.com</p>
                    <p class="text-gray-400 text-sm">Phone: +62 812 3456 7890</p>
                    <a href="{{ route('pengaduan') }}" class="text-red-400 text-sm">Pengaduan</a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2024 Merchandise Store. Semua hak dilindungi.</p>
            </div>
        </div>
    </section>

    <!-- FLOATING BUTTON & CHATBOX -->
    <button
        onclick="toggleChat()"
        id="chat-btn"
        class="fixed bottom-6 right-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white w-14 md:w-16 h-14 md:h-16 rounded-full shadow-2xl text-xl md:text-2xl hover:scale-110 transition-all z-40 hover:shadow-purple-500/50 flex items-center justify-center animate-slideInRight"
    >
        💬
    </button>

    <!-- CHATBOX -->
    <div
        id="chat-container"
        class="hidden fixed bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col z-50 animate-scaleIn"
    >
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-2 md:p-6 flex justify-between items-center flex-shrink-0 chat-header">
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-sm md:text-lg leading-tight">🤖 AI</h2>
                <div class="flex items-center gap-1 text-xs md:text-sm text-purple-100">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full pulse-dot"></span>
                    <span class="hidden md:inline">Online</span>
                </div>
            </div>
            <button onclick="toggleChat()" class="hover:bg-purple-500 rounded-lg transition flex-shrink-0 close-btn flex items-center justify-center text-lg md:text-lg">
                ✕
            </button>
        </div>

        <div
            id="chat-box"
            class="flex-1 overflow-y-auto p-2 md:p-6 bg-gradient-to-b from-gray-50 to-white space-y-2 md:space-y-4"
        >
            <div class="chat-message flex justify-start animate-scaleIn">
                <div class="bg-white shadow-sm p-2 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200">
                    <p class="text-gray-800 font-medium text-xs md:text-base">Halo 👋</p>
                    <p class="text-gray-600 text-xs md:text-sm mt-0.5">Ada yang bisa dibantu?</p>
                </div>
            </div>
        </div>



        <!-- INPUT AREA -->
        <div class="p-2 md:p-4 border-t border-gray-200 bg-white flex-shrink-0 chat-input-area">
            <div class="flex gap-2">
                <textarea
                    id="message"
                    placeholder="Tanya produk..."
                    rows="1"
                    class="flex-1 border border-gray-300 rounded-lg md:rounded-xl px-2 md:px-4 py-2 md:py-3 focus:outline-none input-focus transition-all placeholder-gray-400 text-xs md:text-base resize-none overflow-hidden"
                    onkeydown="handleKeyPress(event)"
                    oninput="autoResize(this)"></textarea>
                <button
                    onclick="sendMessage()"
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-2 md:px-6 py-2 rounded-lg md:rounded-xl font-bold hover:shadow-lg transition-all flex items-center justify-center text-base md:text-xl flex-shrink-0"
                >
                    📤
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-1">Enter untuk kirim</p>
        </div>
    </div>

    <script>
        function toggleChat() {
            const chatContainer = document.getElementById('chat-container');
            const chatBtn = document.getElementById('chat-btn');
            chatContainer.classList.toggle('hidden');
            chatBtn.classList.toggle('hidden');
            if (!chatContainer.classList.contains('hidden')) {
                setTimeout(() => {
                    document.getElementById('message').focus();
                }, 100);
            }
        }

        function autoResize(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        }
function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

        async function sendMessage() {
            const input = document.getElementById('message');
            const message = input.value.trim();

            if (message === '') {
                return;
            }

            const chatBox = document.getElementById('chat-box');

            // Ambil riwayat chat dari sessionStorage
            let history = JSON.parse(sessionStorage.getItem('chat_history')) || [];
            
            // Masukkan pesan user ke dalam riwayat lokal
            history.push({ role: 'user', content: message });
            sessionStorage.setItem('chat_history', JSON.stringify(history));

            // TAMPILKAN PESAN USER
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'chat-message flex justify-end';
            userMessageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm max-w-[85%] shadow-md message-bubble animate-scaleIn">
                    ${marked.parse(message)}
                </div>
            `;
            chatBox.appendChild(userMessageDiv);

            // KOSONGKAN INPUT
            input.value = '';
            input.focus();

            // AUTO SCROLL
            chatBox.scrollTop = chatBox.scrollHeight;

            // TAMPILKAN LOADING
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'chat-message flex justify-start';
            loadingDiv.id = 'loading-message';
            loadingDiv.innerHTML = `
                <div class="bg-white shadow-sm p-2 md:p-4 rounded-2xl rounded-tl-sm border border-gray-200">
                    <div class="loading-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;
            chatBox.appendChild(loadingDiv);
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await fetch(window.location.origin + '/chat-ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        message: message,
                        history: JSON.stringify(history)
                    })
                });

                const data = await response.json();
                const aiReply = data.reply || 'Maaf, saya tidak bisa merespon sekarang. Coba lagi nanti.';

                // HAPUS LOADING
                const loadingMsg = document.getElementById('loading-message');
                if (loadingMsg) loadingMsg.remove();

                // Masukkan respon AI ke riwayat lokal
                if (data.history) {
                    sessionStorage.setItem('chat_history', data.history);
                } else {
                    history.push({ role: 'assistant', content: aiReply });
                    sessionStorage.setItem('chat_history', JSON.stringify(history));
                }

                // TAMPILKAN REPLY AI
                const aiMessageDiv = document.createElement('div');
                aiMessageDiv.className = 'chat-message flex justify-start';
                aiMessageDiv.innerHTML = `
                    <div class="bg-white shadow-sm p-3 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200 message-bubble animate-scaleIn">
                        <div class="text-gray-800 text-xs md:text-base">${marked.parse(aiReply)}</div>
                    </div>
                `;
                chatBox.appendChild(aiMessageDiv);


                // AUTO SCROLL
                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (error) {
                console.error('Error:', error);

                // HAPUS LOADING
                const loadingMsg = document.getElementById('loading-message');
                if (loadingMsg) loadingMsg.remove();

                // TAMPILKAN ERROR
                const errorDiv = document.createElement('div');
                errorDiv.className = 'chat-message flex justify-start';
                errorDiv.innerHTML = `
                    <div class="bg-red-500 text-white p-2 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] shadow-md text-xs md:text-base">
                        ⚠️ Terjadi kesalahan. Silakan coba lagi.
                    </div>
                `;
                chatBox.appendChild(errorDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Intercept clicks on links inside chat box that point to products
        document.getElementById('chat-box').addEventListener('click', function(event) {
            const target = event.closest ? event.target.closest('a') : event.target;
            if (target && target.tagName === 'A' && target.getAttribute('href') && target.getAttribute('href').startsWith('#produk-')) {
                event.preventDefault();
                const elementId = target.getAttribute('href').substring(1);
                const element = document.getElementById(elementId);
                if (element) {
                    // Close the chat box
                    toggleChat();
                    
                    // Wait a bit for the chat close animation, then scroll smoothly
                    setTimeout(() => {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // Apply the highlight flashing animation
                        element.classList.add('highlight-product');
                        setTimeout(() => {
                            element.classList.remove('highlight-product');
                        }, 1500);
                    }, 300);
                }
            }
        });

        // Load existing session history into markdown and scroll to bottom
        document.addEventListener('DOMContentLoaded', () => {
            const chatBox = document.getElementById('chat-box');
            const history = JSON.parse(sessionStorage.getItem('chat_history')) || [];
            
            if (history.length > 0) {
                // Bersihkan ucapan selamat datang bawaan jika ada riwayat
                chatBox.innerHTML = '';
                
                history.forEach(msg => {
                    if (msg.role === 'system') return;
                    if (msg.role === 'assistant' && (!msg.content || msg.content.trim() === '')) return;
                    
                    const messageDiv = document.createElement('div');
                    if (msg.role === 'user') {
                        messageDiv.className = 'chat-message flex justify-end';
                        messageDiv.innerHTML = `
                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm max-w-[85%] shadow-md message-bubble">
                                <div>${marked.parse(msg.content)}</div>
                            </div>
                        `;
                    } else if (msg.role === 'assistant') {
                        messageDiv.className = 'chat-message flex justify-start';
                        messageDiv.innerHTML = `
                            <div class="bg-white shadow-sm p-3 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200 message-bubble">
                                <div class="text-gray-800 text-xs md:text-base">${marked.parse(msg.content)}</div>
                            </div>
                        `;
                    }
                    chatBox.appendChild(messageDiv);
                });
            }
            
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });


        // ===== FILTER KATEGORI =====
        function filterCategory(category) {
            const cards = document.querySelectorAll('.product-card');
            const tabs = document.querySelectorAll('.tab-btn');

            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active-tab'));
            const activeTab = document.getElementById(category === 'all' ? 'tab-all' : 'tab-' + category);
            if (activeTab) activeTab.classList.add('active-tab');

            // Show/hide cards
            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (category === 'all' || cardCategory === category) {
                    card.classList.remove('hidden-card');
                } else {
                    card.classList.add('hidden-card');
                }
            });
        }
    </script>

</body>
</html>