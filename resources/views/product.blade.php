<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product['name'] }} - AM Merchandise</title>
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-scaleIn {
            animation: scaleIn 0.3s ease-out;
        }

        .pulse-dot {
            animation: bounce 2s infinite;
        }

        .chat-message {
            animation: scaleIn 0.3s ease-out;
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

        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }

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
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-bold gradient-text">AM Merchandise</span>
            </a>
            <nav class="space-x-8 hidden md:flex items-center">
                <a href="/" class="text-gray-600 font-medium hover:text-purple-600 transition">Beranda</a>
                <a href="/#produk" class="text-gray-600 font-medium hover:text-purple-600 transition">Produk</a>
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all hover-lift">WhatsApp</a>
            </nav>
        </div>
    </header>

    <!-- MAIN PRODUCT DETAILS -->
    <main class="flex-grow max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-12 w-full animate-fadeIn">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2 gap-8 p-6 md:p-12">
            
            <!-- LEFT: PRODUCT IMAGE -->
            <div class="flex items-center justify-center bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 p-4 relative group">
                <div class="absolute top-4 left-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-md">
                    {{ $product['category'] }}
                </div>
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="rounded-xl w-full h-80 md:h-[480px] object-cover hover:scale-105 transition-transform duration-500 shadow-sm">
            </div>

            <!-- RIGHT: DETAILED SPECS -->
            <div class="flex flex-col justify-between">
                <div>
                    <nav class="text-sm text-gray-400 mb-4">
                        <a href="/" class="hover:text-purple-600">Home</a> &gt; 
                        <span class="text-gray-600 font-medium">{{ $product['name'] }}</span>
                    </nav>

                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-3">
                        {{ $product['name'] }}
                    </h1>

                    <div class="text-2xl md:text-3xl font-extrabold text-indigo-600 mb-6">
                        {{ $product['price'] }}
                    </div>

                    <div class="border-t border-b border-gray-100 py-6 mb-6">
                        <h3 class="font-bold text-gray-900 mb-2">Deskripsi Produk</h3>
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                            {{ $product['description'] }}
                        </p>
                    </div>

                    <!-- FEATURES -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-900 mb-3">Keunggulan & Detail</h3>
                        <ul class="space-y-2">
                            @foreach($product['features'] as $feature)
                            <li class="flex items-start gap-2.5 text-sm md:text-base text-gray-600">
                                <span class="text-green-500 font-bold">✓</span>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- SIZE OPTIONS -->
                    <div class="mb-8">
                        <h3 class="font-bold text-gray-900 mb-3">Ukuran Tersedia</h3>
                        <div class="flex gap-2">
                            @foreach($product['sizes'] as $size)
                            <span class="border border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 cursor-pointer font-bold px-4 py-2 rounded-lg text-sm transition-all bg-white shadow-sm flex items-center justify-center min-w-[45px]">
                                {{ $size }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CHECKOUT BUTTON -->
                <div>
                    <a href="/checkout/{{ $product['slug'] }}" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-center py-4 rounded-2xl font-bold text-lg hover:shadow-lg transition-all hover-lift shadow-md block">
                        🛍️ Beli & Isi Form Pemesanan
                    </a>
                    <p class="text-xs text-center text-gray-400 mt-2">Isi data pengiriman & dapatkan struk sukses pemesanan</p>
                </div>

            </div>
        </div>

        <!-- OTHER RECOMMENDATIONS -->
        <section class="mt-16 md:mt-24">
            <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-2">
                <span>🔥</span> Rekomendasi Produk Lainnya
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($otherProducts as $other)
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover-lift group border border-gray-100 p-4 flex gap-4 md:gap-6 items-center">
                    <img src="{{ $other['image'] }}" class="w-24 md:w-32 h-24 md:h-32 object-cover rounded-xl shadow-inner bg-gray-50 flex-shrink-0">
                    <div class="flex-grow min-w-0">
                        <span class="text-xs bg-indigo-50 text-indigo-600 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $other['category'] }}</span>
                        <h3 class="font-bold text-base md:text-xl text-gray-900 mt-1 truncate">{{ $other['name'] }}</h3>
                        <p class="text-indigo-600 font-extrabold text-sm md:text-base mt-0.5">{{ $other['price'] }}</p>
                        <a href="/product/{{ $other['slug'] }}" class="mt-3 inline-flex items-center text-xs font-bold text-purple-600 hover:text-indigo-800 transition">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="py-12 px-4 md:px-6 bg-gray-900 text-white mt-20">
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
                        <li><a href="/" class="hover:text-white">Beranda</a></li>
                        <li><a href="https://wa.me/6281234567890" target="_blank" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">📞 Hubungi</h3>
                    <p class="text-gray-400 text-sm">Email: info@merchandise.com</p>
                    <p class="text-gray-400 text-sm">Phone: +62 812 3456 7890</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2024 Merchandise Store. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- CHATBOT FLOATING BUTTON -->
    <button
        onclick="toggleChat()"
        id="chat-btn"
        class="fixed bottom-6 right-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white w-14 md:w-16 h-14 md:h-16 rounded-full shadow-2xl text-xl md:text-2xl hover:scale-110 transition-all z-40 hover:shadow-purple-500/50 flex items-center justify-center"
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
            <button onclick="toggleChat()" class="hover:bg-purple-500 rounded-lg transition flex-shrink-0 close-btn flex items-center justify-center text-lg">
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
                <input
                    type="text"
                    id="message"
                    placeholder="Tanya produk..."
                    class="flex-1 border border-gray-300 rounded-lg md:rounded-xl px-2 md:px-4 py-2 md:py-3 focus:outline-none input-focus transition-all placeholder-gray-400 text-xs md:text-base"
                    onkeydown="handleKeyPress(event)"
                >
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

    <!-- SCRIPTS -->
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

        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        async function sendMessage() {
            const input = document.getElementById('message');
            const message = input.value.trim();

            if (message === '') return;

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

            input.value = '';
            input.focus();
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

                const loadingMsg = document.getElementById('loading-message');
                if (loadingMsg) loadingMsg.remove();

                // Masukkan respon AI ke riwayat lokal
                history.push({ role: 'assistant', content: aiReply });
                sessionStorage.setItem('chat_history', JSON.stringify(history));

                const aiMessageDiv = document.createElement('div');
                aiMessageDiv.className = 'chat-message flex justify-start';
                aiMessageDiv.innerHTML = `
                    <div class="bg-white shadow-sm p-3 md:p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200 message-bubble animate-scaleIn">
                        <div class="text-gray-800 text-xs md:text-base">${marked.parse(aiReply)}</div>
                    </div>
                `;
                chatBox.appendChild(aiMessageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (error) {
                console.error('Error:', error);

                const loadingMsg = document.getElementById('loading-message');
                if (loadingMsg) loadingMsg.remove();

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

        // Load existing session history into markdown and scroll to bottom
        document.addEventListener('DOMContentLoaded', () => {
            const chatBox = document.getElementById('chat-box');
            const history = JSON.parse(sessionStorage.getItem('chat_history')) || [];
            
            if (history.length > 0) {
                // Bersihkan ucapan selamat datang bawaan jika ada riwayat
                chatBox.innerHTML = '';
                
                history.forEach(msg => {
                    if (msg.role === 'system') return;
                    
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
    </script>

</body>
</html>
