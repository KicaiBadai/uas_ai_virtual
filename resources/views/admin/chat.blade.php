@extends('admin.layout')

@section('title', 'Chatbot Admin')
@section('page_title', 'AI Command Center')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .chat-bubble-ai {
        background: #ffffff;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-top-left-radius: 4px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .chat-bubble-user {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border-top-right-radius: 4px;
        box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.15);
    }

    .chat-bubble-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-top-left-radius: 4px;
    }

    .loading-dots span {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #8b5cf6;
        animation: chatBounce 1.4s infinite ease-in-out;
        margin: 0 1.5px;
    }
    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes chatBounce {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-6px); }
    }

    /* Markdown Styling */
    .ai-markdown table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.75rem 0;
        font-size: 0.8rem;
    }
    .ai-markdown th {
        background-color: #f9fafb;
        color: #374151;
        font-weight: 700;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
    }
    .ai-markdown td {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
    }
    .ai-markdown tr:nth-child(even) { background-color: #f9fafb; }
    .ai-markdown strong { color: #8b5cf6; font-weight: 700; }
    .ai-markdown ul { list-style-type: disc; margin-left: 1.25rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
    .ai-markdown ol { list-style-type: decimal; margin-left: 1.25rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
    .ai-markdown li  { margin-bottom: 0.25rem; }
    .ai-markdown p   { margin-bottom: 0.5rem; }
    .ai-markdown code {
        background: #f3f4f6;
        border-radius: 4px;
        padding: 0.1rem 0.3rem;
        font-size: 0.75rem;
        color: #7c3aed;
    }

    /* Scroll smooth */
    #admin-chat-box {
        scroll-behavior: smooth;
    }

    /* Clear button */
    #btn-clear-chat {
        transition: opacity 0.2s;
    }
    #btn-clear-chat:hover { opacity: 0.8; }
</style>

<div class="max-w-4xl mx-auto flex flex-col h-[calc(100vh-12rem)] min-h-[500px]">

    <!-- Console Header -->
    <div class="bg-gray-900 rounded-t-3xl px-5 py-3.5 flex items-center justify-between border-b border-gray-800 shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></div>
            <div>
                <h3 class="font-extrabold text-sm text-white tracking-wider">MISTRAL AI ASSISTANT</h3>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Admin Database Agent · AM Merchandise</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button id="btn-clear-chat" onclick="clearChat()"
                class="text-[10px] text-gray-400 hover:text-red-400 font-bold uppercase tracking-wider border border-gray-700 rounded-lg px-2.5 py-1 transition-colors">
                🗑 Hapus Riwayat
            </button>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                <span class="w-3 h-3 rounded-full bg-green-500/80 inline-block"></span>
            </div>
        </div>
    </div>

    <!-- Chat Log Area -->
    <div class="flex-1 overflow-y-auto p-6 bg-slate-50 border-x border-gray-200 glass-panel flex flex-col space-y-4" id="admin-chat-box">
        <!-- AI Greeting -->
        <div class="flex justify-start" id="greeting-bubble">
            <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-ai">
                <p class="font-bold text-xs text-purple-600 uppercase tracking-wider mb-2">🤖 Asisten Admin</p>
                <div class="text-sm text-gray-800 leading-relaxed">
                    Halo Administrator! Saya siap membantu Anda mengelola data penjualan dan pesanan <strong>AM Merchandise</strong>.
                    <br><br>
                    Coba tanyakan:
                    <ul class="list-disc ml-5 mt-2 space-y-1 text-xs text-gray-600">
                        <li><strong>«Tampilkan pesanan pending»</strong></li>
                        <li><strong>«Cari pesanan atas nama Afnan»</strong></li>
                        <li><strong>«Tambah 5 stok topi akatsuki»</strong></li>
                        <li><strong>«Ubah status resi AM12345 menjadi dikirim»</strong></li>
                        <li><strong>«Laporan penjualan bulan ini»</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Input Area -->
    <div class="p-4 bg-white border border-gray-200 rounded-b-3xl shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex-grow relative">
                <textarea
                    id="admin-chat-message"
                    placeholder="Tulis instruksi ke AI... (Enter untuk kirim)"
                    rows="1"
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium resize-none overflow-hidden"
                    onkeydown="handleAdminKeyPress(event)"
                    oninput="autoResize(this)"
                ></textarea>
            </div>
            <button
                id="btn-send"
                onclick="sendAdminMessage()"
                class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 hover:shadow-lg transition-all py-3.5 px-6 rounded-2xl text-white font-bold text-sm tracking-wider flex items-center justify-center gap-2 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span>Kirim</span> ⚡
            </button>
        </div>
        <p class="text-[10px] text-gray-400 mt-2 ml-1">
            AI dapat mencari pesanan, memperbarui status, mengelola stok produk, dan merangkum laporan penjualan.
        </p>
    </div>
</div>

<script>
    // ── State ──────────────────────────────────────────────────────────
    let chatHistory = JSON.parse(sessionStorage.getItem('admin_chat_history')) || [];
    let isSending   = false;

    // ── On Load: render existing history ──────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const chatBox = document.getElementById('admin-chat-box');

        // Recreate greeting bubble with suggestion list
        const greeting = document.createElement('div');
        greeting.id = 'greeting-bubble';
        greeting.className = 'flex justify-start';
        greeting.innerHTML = `
            <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-ai">
                <p class="font-bold text-xs text-purple-600 uppercase tracking-wider mb-2">🤖 Asisten Admin</p>
                <div class="text-sm text-gray-800 leading-relaxed">
                    Halo Administrator! Saya siap membantu Anda mengelola data penjualan dan pesanan <strong>AM Merchandise</strong>.
                    <br><br>
                    Coba tanyakan:
                    <ul class="list-disc ml-5 mt-2 space-y-1 text-xs text-gray-600">
                        <li><strong>«Tampilkan pesanan pending»</strong></li>
                        <li><strong>«Cari pesanan atas nama Afnan»</strong></li>
                        <li><strong>«Tambah 5 stok topi akatsuki»</strong></li>
                        <li><strong>«Ubah status resi AM12345 menjadi dikirim»</strong></li>
                        <li><strong>«Laporan penjualan bulan ini»</strong></li>
                    </ul>
                </div>
            </div>
        `;

        // Clear any existing content and insert greeting
        chatBox.innerHTML = '';
        chatBox.appendChild(greeting);

        // Render stored history below greeting
        if (chatHistory.length > 0) {
            chatHistory.forEach(msg => {
                if (msg.role === 'user' || (msg.role === 'assistant' && msg.content && msg.content.trim() !== '')) {
                    renderMessage(msg.role, msg.content);
                }
            });
            scrollBottom();
        }
    });

    // ── Key handler ───────────────────────────────────────────────────
    function handleAdminKeyPress(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendAdminMessage();
        }
    }

    function copyMessage(btn) {
        const messageDiv = btn.closest('.chat-bubble-user, .chat-bubble-ai');
        const text = messageDiv.querySelector('.text-sm').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Pesan berhasil disalin');
        });
    }

    function showTyping() {
        const chatBox = document.getElementById('admin-chat-box');
        const typingEl = document.createElement('div');
        typingEl.id = 'typing-indicator';
        typingEl.className = 'flex justify-start';
        typingEl.innerHTML = `
            <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-ai" style="background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 100%);">
                <p class="font-bold text-xs text-purple-600 uppercase tracking-wider mb-2">🤖 Asisten Admin</p>
                <div class="text-sm text-gray-800 leading-relaxed">Sedang mengetik...</div>
            </div>`;
        chatBox.appendChild(typingEl);
        scrollBottom();
    }

    function hideTyping() {
        const typingEl = document.getElementById('typing-indicator');
        if (typingEl) typingEl.remove();
    }

    async function sendAdminMessage() {
        if (isSending) return;
        const input   = document.getElementById('admin-chat-message');
        const message = input.value.trim();
        if (!message) return;

        isSending = true;
        document.getElementById('btn-send').disabled = true;

        chatHistory.push({ role: 'user', content: message });
        sessionStorage.setItem('admin_chat_history', JSON.stringify(chatHistory));
        renderMessage('user', message);

        input.value = '';
        input.focus();

        showTyping();

        const loadingId = 'admin-loading-bubble';
        const chatBox   = document.getElementById('admin-chat-box');
        const loadingEl = document.createElement('div');
        loadingEl.id        = loadingId;
        loadingEl.className = 'flex justify-start';
        loadingEl.innerHTML = `
            <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-ai flex items-center gap-2">
                <p class="font-bold text-xs text-purple-600 uppercase tracking-wider">🤖</p>
                <div class="loading-dots"><span></span><span></span><span></span></div>
            </div>`;
        chatBox.appendChild(loadingEl);
        scrollBottom();

        try {
            const response = await fetch('{{ route("admin.chat-ai") }}', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    history: JSON.stringify(chatHistory.slice(-10))
                })
            });

            document.getElementById(loadingId)?.remove();
            hideTyping();

            let data = {};
            const contentType = response.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                data = await response.json();
            } else {
                renderMessage('error', `Terjadi kesalahan server (${response.status}).`);
                return;
            }

            if (!response.ok) {
                renderMessage('error', data.reply || `Terjadi kesalahan server (${response.status}).`);
            } else {
                const reply = data.reply || 'Maaf, terjadi masalah saat memproses tanggapan AI.';
                if (data.history) {
                    chatHistory = JSON.parse(data.history);
                } else {
                    chatHistory.push({ role: 'assistant', content: reply });
                }
                sessionStorage.setItem('admin_chat_history', JSON.stringify(chatHistory));
                renderMessage('assistant', reply);
            }
        } catch (networkError) {
            document.getElementById(loadingId)?.remove();
            hideTyping();
            renderMessage('error', 'Gagal mengirim pesan. Periksa koneksi internet Anda.');
        } finally {
            isSending = false;
            document.getElementById('btn-send').disabled = false;
        }
    }

    function renderMessage(role, content) {
        const chatBox = document.getElementById('admin-chat-box');
        const wrapper = document.createElement('div');
        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const copyBtn = `<button class="text-xs text-gray-500 hover:text-purple-600 ml-2" onclick="copyMessage(this)"><i class="fas fa-copy"></i></button>`;

        if (role === 'user') {
            wrapper.className = 'flex justify-end';
            wrapper.innerHTML = `
                <div class="max-w-[85%] rounded-2xl p-4 text-white chat-bubble-user" style="background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 100%);">
                    <p class="font-bold text-[10px] text-purple-200 uppercase tracking-widest mb-1 text-right">👤 Admin</p>
                    <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                    <div class="flex items-center justify-between text-xs text-gray-200 mt-1">
                        <span class="timestamp">${timestamp}</span>
                        ${copyBtn}
                    </div>
                </div>`;
        } else if (role === 'assistant') {
            wrapper.className = 'flex justify-start';
            wrapper.innerHTML = `
                <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-ai" style="background: #ffffff;">
                    <p class="font-bold text-xs text-purple-600 uppercase tracking-wider mb-2">🤖 Asisten Admin</p>
                    <div class="text-sm text-gray-800 leading-relaxed ai-markdown">${marked.parse(content)}</div>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                        <span class="timestamp">${timestamp}</span>
                        ${copyBtn}
                    </div>
                </div>`;
        } else if (role === 'error') {
            wrapper.className = 'flex justify-start';
            wrapper.innerHTML = `
                <div class="max-w-[85%] rounded-2xl p-4 chat-bubble-error">
                    <p class="font-bold text-xs text-red-600 uppercase tracking-wider mb-1">⚠️ Kesalahan Sistem</p>
                    <p class="text-xs leading-relaxed">${escapeHtml(content)}</p>
                </div>`;
        }

        chatBox.appendChild(wrapper);
        scrollBottom();
    }

    // ── Clear chat ────────────────────────────────────────────────────
    function clearChat() {
        if (!confirm('Hapus seluruh riwayat obrolan sesi ini?')) return;
        chatHistory = [];
        sessionStorage.removeItem('admin_chat_history');
        const chatBox = document.getElementById('admin-chat-box');
        chatBox.innerHTML = '';

        // Restore greeting with full suggestion list
        const greeting = document.createElement('div');
        greeting.id = 'greeting-bubble';
        greeting.className = 'flex justify-start';
        greeting.innerHTML = `
            <div class=\"max-w-[85%] rounded-2xl p-4 chat-bubble-ai\">
                <p class=\"font-bold text-xs text-purple-600 uppercase tracking-wider mb-2\">🤖 Asisten Admin</p>
                <div class=\"text-sm text-gray-800 leading-relaxed\">
                    Halo Administrator! Saya siap membantu Anda mengelola data penjualan dan pesanan <strong>AM Merchandise</strong>.
                    <br><br>
                    Coba tanyakan:
                    <ul class=\"list-disc ml-5 mt-2 space-y-1 text-xs text-gray-600\">
                        <li><strong>«Tampilkan pesanan pending»</strong></li>
                        <li><strong>«Cari pesanan atas nama Afnan»</strong></li>
                        <li><strong>«Tambah 5 stok topi akatsuki»</strong></li>
                        <li><strong>«Ubah status resi AM12345 menjadi dikirim»</strong></li>
                        <li><strong>«Laporan penjualan bulan ini»</strong></li>
                    </ul>
                </div>
            </div>`;
        chatBox.appendChild(greeting);
    }

    // ── Helpers ───────────────────────────────────────────────────────
    function scrollBottom() {
        const chatBox = document.getElementById('admin-chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endsection
