@extends('admin.layout')

@section('title', 'Kelola API & Dataset AI')
@section('page_title', 'Pengaturan AI & Input Dataset')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <!-- Intro Card -->
    <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-2">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            🤖 <span>Konfigurasi Inteligensi Toko</span>
        </h3>
        <p class="text-sm text-gray-500">
            Halaman ini mengintegrasikan asisten AI toko dengan **Mistral AI API**. Anda bisa mengubah API Key, model pemrosesan, intruksi dasar (System Prompt), serta **memasukkan dataset kustom** (informasi operasional toko, FAQ, kebijakan belanja) agar asisten AI dapat merespons pelanggan dengan tepat.
        </p>
    </div>

    <!-- Configuration Form -->
    <form action="{{ route('admin.ai-settings.update') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Card 1: API Settings -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                🔑 <span>1. Kredensial & Model API</span>
            </h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- API Key -->
                <div class="sm:col-span-2">
                    <label for="mistral_api_key" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mistral API Key</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="mistral_api_key" 
                            id="mistral_api_key" 
                            value="{{ old('mistral_api_key', $settings['mistral_api_key']) }}" 
                            placeholder="Masukkan Mistral API Key Anda..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 font-mono"
                        >
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-3.5 text-xs text-purple-600 font-semibold hover:underline">
                            Lihat/Sembunyikan
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Dapatkan API key gratis atau premium langsung dari dashboard Mistral AI.</p>
                </div>

                <!-- Model Choice -->
                <div>
                    <label for="mistral_model" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Model Pemrosesan (Model Name)</label>
                    <select 
                        name="mistral_model" 
                        id="mistral_model" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 bg-white"
                    >
                        <option value="open-mistral-7b" {{ old('mistral_model', $settings['mistral_model']) === 'open-mistral-7b' ? 'selected' : '' }}>open-mistral-7b (Default - Ringan)</option>
                        <option value="mistral-tiny" {{ old('mistral_model', $settings['mistral_model']) === 'mistral-tiny' ? 'selected' : '' }}>mistral-tiny</option>
                        <option value="mistral-small-latest" {{ old('mistral_model', $settings['mistral_model']) === 'mistral-small-latest' ? 'selected' : '' }}>mistral-small-latest (Lebih Akurat)</option>
                        <option value="mistral-medium-latest" {{ old('mistral_model', $settings['mistral_model']) === 'mistral-medium-latest' ? 'selected' : '' }}>mistral-medium-latest (Cerdas/Kompleks)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Card 2: System Prompt -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                ✍️ <span>2. Instruksi Dasar AI (System Prompt)</span>
            </h4>

            <div>
                <label for="system_prompt" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System Prompt Template</label>
                <textarea 
                    name="system_prompt" 
                    id="system_prompt" 
                    rows="8" 
                    required 
                    placeholder="Instruksi kepribadian dan aturan asisten AI..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 font-mono leading-relaxed"
                >{{ old('system_prompt', $settings['system_prompt']) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Mengatur bagaimana AI harus bersikap, cara berkomunikasi, larangan-larangan kata, dan instruksi link.</p>
            </div>
        </div>

        <!-- Card 3: Custom Dataset Input -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-3 flex justify-between items-center">
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    📊 <span>3. Input Dataset Kustom (Knowledge Base)</span>
                </h4>
                <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full">New Feature</span>
            </div>

            <div>
                <label for="dataset_content" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dataset Toko Tambahan</label>
                <textarea 
                    name="dataset_content" 
                    id="dataset_content" 
                    rows="8" 
                    placeholder="Contoh:
- Jam operasional: 09.00 - 21.00
- Rekening pembayaran: BCA 123456789 a/n Aditya
- Diskon Khusus: Hari Jumat diskon 10% untuk produk Hoodie..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition text-sm text-gray-800 font-mono leading-relaxed"
                >{{ old('dataset_content', $settings['dataset_content']) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Tulis data-data penting, FAQ, atau info khusus di sini. AI akan mempelajari teks ini dan menggunakannya untuk menjawab pertanyaan customer yang berkaitan.</p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-end gap-3 pt-2">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm transition shadow-lg shadow-purple-500/20 active:scale-98">
                💾 Simpan Semua Konfigurasi
            </button>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('mistral_api_key');
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>
@endsection
