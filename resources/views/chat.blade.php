<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchandise Store AI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

        .animate-slideInUp {
            animation: slideInUp 0.5s ease-out;
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
            white-space: pre-line;
            line-height: 1.7;
            font-size: 15px;
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">

    <!-- HEADER -->
    <header class="bg-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold gradient-text">
                    ✨ Merchandise Store
                </h1>
                <p class="text-gray-500 text-sm">Koleksi fashion terbaik untuk gaya kamu</p>
            </div>

            <nav class="space-x-8 hidden md:block">
                <a href="#" class="text-gray-700 font-medium hover:text-purple-600 transition">Home</a>
                <a href="#" class="text-gray-700 font-medium hover:text-purple-600 transition">Produk</a>
                <a href="#" class="text-gray-700 font-medium hover:text-purple-600 transition">Kategori</a>
                <a href="#" class="text-gray-700 font-medium hover:text-purple-600 transition">Kontak</a>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-900 text-white py-24 overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10">
            <div class="animate-slideInUp">
                <h2 class="text-6xl font-bold leading-tight mb-6">
                    Merchandise Keren Untuk Semua Style
                </h2>
                <p class="text-purple-100 text-lg mb-8 leading-relaxed">
                    Hoodie anime, kaos gaming, topi streetwear, dan fashion terbaik untuk mengekspresikan gaya unikmu.
                </p>
                <button class="bg-white text-purple-700 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all hover-lift shadow-lg">
                    🛍️ Belanja Sekarang
                </button>
            </div>

            <div class="grid grid-cols-2 gap-6 animate-fadeIn">
                <div class="bg-white rounded-2xl p-5 shadow-xl hover-lift">
                    <img src="https://placehold.co/300x200/667eea/ffffff?text=Hoodie+Anime" class="rounded-xl mb-4 w-full h-40 object-cover">
                    <h3 class="font-bold text-lg text-gray-900">Hoodie Anime</h3>
                    <p class="text-purple-600 font-semibold">Rp 180.000</p>
                    <p class="text-gray-500 text-sm mt-1">Desain eksklusif</p>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-xl hover-lift">
                    <img src="https://placehold.co/300x200/764ba2/ffffff?text=Kaos+Gaming" class="rounded-xl mb-4 w-full h-40 object-cover">
                    <h3 class="font-bold text-lg text-gray-900">Kaos Gaming</h3>
                    <p class="text-purple-600 font-semibold">Rp 95.000</p>
                    <p class="text-gray-500 text-sm mt-1">Nyaman dipakai</p>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-xl hover-lift col-span-2">
                    <img src="https://placehold.co/300x200/667eea/ffffff?text=Topi+Streetwear" class="rounded-xl mb-4 w-full h-40 object-cover">
                    <h3 class="font-bold text-lg text-gray-900">Topi Streetwear</h3>
                    <p class="text-purple-600 font-semibold">Rp 120.000</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUK -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    🔥 Produk Populer
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Koleksi pilihan terbaik kami yang paling dicari oleh pelanggan setia
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- CARD 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover-lift group">
                    <div class="relative overflow-hidden bg-gradient-to-br from-purple-200 to-indigo-200 h-64">
                        <img src="https://placehold.co/500x300/667eea/ffffff?text=Topi+Streetwear" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4 bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-bold">
                            Trending
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2 text-gray-900">Topi Streetwear</h3>
                        <p class="text-gray-600 mb-4">Fashion modern untuk outfit harian kamu dengan desain yang minimalis dan elegan.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold gradient-text">Rp 120K</span>
                            <button class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Beli
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover-lift group">
                    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-200 to-purple-200 h-64">
                        <img src="https://placehold.co/500x300/764ba2/ffffff?text=Hoodie+Oversize" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4 bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-bold">
                            Bestseller
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2 text-gray-900">Hoodie Oversize</h3>
                        <p class="text-gray-600 mb-4">Nyaman dan stylish dipakai untuk aktivitas sehari-hari atau bersantai di rumah.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold gradient-text">Rp 180K</span>
                            <button class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Beli
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover-lift group">
                    <div class="relative overflow-hidden bg-gradient-to-br from-purple-200 to-pink-200 h-64">
                        <img src="https://placehold.co/500x300/667eea/ffffff?text=Kaos+Anime" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4 bg-pink-600 text-white px-4 py-2 rounded-full text-sm font-bold">
                            New
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2 text-gray-900">Kaos Anime</h3>
                        <p class="text-gray-600 mb-4">Desain anime premium terbaru dengan kualitas cetak terbaik dan material berkualitas tinggi.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold gradient-text">Rp 95K</span>
                            <button class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Beli
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FLOATING BUTTON & CHATBOX -->
    <button
        onclick="toggleChat()"
        class="fixed bottom-6 right-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white w-16 h-16 rounded-full shadow-2xl text-2xl hover:scale-110 transition-all z-40 hover:shadow-purple-500/50 flex items-center justify-center"
    >
        💬
    </button>

    <!-- CHATBOX -->
    <div
        id="chat-container"
        class="hidden fixed bottom-24 right-6 w-[400px] h-[600px] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col z-50 animate-scaleIn md:w-[420px]"
    >
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg">🤖 AI Assistant</h2>
                <div class="flex items-center gap-2 text-sm text-purple-100">
                    <span class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></span>
                    Online
                </div>
            </div>
            <button onclick="toggleChat()" class="hover:bg-purple-500 p-2 rounded-lg transition">
                ✕
            </button>
        </div>

        <!-- CHAT MESSAGES -->
        <div
            id="chat-box"
            class="flex-1 overflow-y-auto p-6 bg-gradient-to-b from-gray-50 to-white space-y-4"
        >
            <div class="chat-message flex justify-start">
                <div class="bg-white shadow-sm p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200">
                    <p class="text-gray-800 font-medium">Halo 👋</p>
                    <p class="text-gray-600 text-sm mt-1">Saya AI Assistant Merchandise kamu. Ada yang bisa aku bantu?</p>
                </div>
            </div>
        </div>

        <!-- INPUT AREA -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <div class="flex gap-3">
                <input
                    type="text"
                    id="message"
                    placeholder="Tanya tentang produk..."
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none input-focus transition-all placeholder-gray-400"
                    onkeydown="handleKeyPress(event)"
                >
                <button
                    onclick="sendMessage()"
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 rounded-xl font-bold hover:shadow-lg transition-all flex items-center justify-center"
                >
                    📤
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-2">Tekan Enter atau klik tombol untuk mengirim</p>
        </div>
    </div>

    <script>
        function toggleChat() {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.classList.toggle('hidden');
            if (!chatContainer.classList.contains('hidden')) {
                document.getElementById('message').focus();
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

            if (message === '') {
                return;
            }

            const chatBox = document.getElementById('chat-box');

            // TAMPILKAN PESAN USER
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'chat-message flex justify-end';
            userMessageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 rounded-2xl rounded-tr-sm max-w-[85%] shadow-md message-bubble">
                    ${escapeHtml(message)}
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
                <div class="bg-white shadow-sm p-4 rounded-2xl rounded-tl-sm border border-gray-200">
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
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                const aiReply = data.reply || 'Maaf, saya tidak bisa merespon sekarang. Coba lagi nanti.';

                // HAPUS LOADING
                const loadingMsg = document.getElementById('loading-message');
                if (loadingMsg) loadingMsg.remove();

                // TAMPILKAN REPLY AI
                const aiMessageDiv = document.createElement('div');
                aiMessageDiv.className = 'chat-message flex justify-start';
                aiMessageDiv.innerHTML = `
                    <div class="bg-white shadow-sm p-4 rounded-2xl rounded-tl-sm max-w-[85%] border border-gray-200 message-bubble">
                        <p class="text-gray-800">${escapeHtml(aiReply)}</p>
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
                    <div class="bg-red-500 text-white p-4 rounded-2xl rounded-tl-sm max-w-[85%] shadow-md">
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
    </script>

</body>
</html>