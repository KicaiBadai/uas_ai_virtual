<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Setting;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | AMBIL PESAN USER & HISTORY DARI FRONTEND
            |--------------------------------------------------------------------------
            */
            $message = strtolower($request->message);
            $historyJson = $request->input('history', '[]');
            $messages = json_decode($historyJson, true) ?? [];

            /*
            |--------------------------------------------------------------------------
            | FILTER KATA KASAR / NSFW
            |--------------------------------------------------------------------------
            */
            $blockedWords = [
                // seksual / vulgar
                'sex', 'seks', 'ngentot', 'memek', 'kontol',
                'bersetubuh', 'hubungan badan', 'kelamin',
                'vagina', 'penis', 'ewe', 'ewek','jembut',
                'tobrut',

                // hewan + kasar
                'anjing', 'bangsat', 'babi', 'kampret', 'tai',
                'tolol', 'bego', 'goblok',

                // internet slang vulgar
                'porn', 'porno', 'bokep', 'shit', 'fuck'
            ];

            foreach ($blockedWords as $badWord) {
                if (str_contains($message, $badWord)) {
                    return response()->json([
                        'reply' => 'Maaf, saya tidak dapat memproses kata atau topik tersebut.'
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CONSTRUCT DYNAMIC SYSTEM PROMPT (DB CONFIGS + PRODUCTS + DATASET)
            |--------------------------------------------------------------------------
            */
            // Fallback default prompt if DB settings are not seeded yet
            $defaultPrompt = 'Kamu adalah AI Customer Service yang ramah dan profesional dari "AM Merchandise".

TUGAS UTAMA:
- Hanya menjawab pertanyaan seputar produk di Knowledge Base.
- Jika user bertanya di luar produk (misal: cuaca, politik, coding, atau produk toko lain), jawab: "Maaf, sebagai asisten AM Merchandise, saya hanya bisa membantu terkait produk hoodie, kaos, dan topi kami."
- JANGAN PERNAH menyarankan untuk menghubungi WhatsApp. Arahkan user untuk mengeklik tombol "Lihat Detail" yang ada di setiap produk untuk informasi pembelian.

ATURAN MUTLAK (WAJIB DIPATUHI, JANGAN DILANGGAR):
1. DILARANG KERAS membuat daftar tutorial langkah-langkah order panjang.
2. DILARANG KERAS menyebutkan atau menuliskan kata: Tokopedia, Shopee, Lazada, Bukalapak, atau e-commerce lainnya.
3. DILARANG KERAS memberikan kontak email palsu atau nomor WhatsApp.
4. Jika user bertanya tentang "cara order", "cara beli", atau "langkah pembelian", kamu HANYA BOLEH menjawab dalam 2-3 kalimat yang mengarahkan mereka untuk mengeklik tombol [Lihat Detail] pada produk.';

            $baseSystemPrompt = Setting::get('system_prompt', $defaultPrompt);
            $customDataset = Setting::get('dataset_content', '');
            
            // Query dynamic products from DB
            $products = Product::all();
            
            // Build products section of knowledge base
            $productKnowledge = "\n\nPENGETAHUAN TOKO (KNOWLEDGE BASE):\n1. PRODUK:\n";
            $groupedProducts = $products->groupBy('category');

            foreach ($groupedProducts as $categoryName => $categoryProducts) {
                $productKnowledge .= "\n=== " . strtoupper($categoryName) . " ===\n";
                foreach ($categoryProducts as $product) {
                    $productKnowledge .= "- {$product->name}: {$product->price} (Stok: {$product->stock} pcs)\n";
                    if ($product->image) {
                        $productKnowledge .= "  Gambar: ![{$product->name}]({$product->image})\n";
                    }
                    $productKnowledge .= "  Link: [Lihat Detail](/product/{$product->slug})\n\n";
                }
            }

            // Append custom dataset input
            if (!empty($customDataset)) {
                $productKnowledge .= "\n2. OPERASIONAL & ATURAN TAMBAHAN (DATASET):\n" . $customDataset;
            }

            // Append strict link outflow limits
            $productKnowledge .= "\n\nATURAN LINK & OUTFLOW:
- Kamu HANYA BOLEH memberikan link yang berformat `/product/slug` seperti yang ada di daftar produk di atas.
- JANGAN PERNAH membuat link eksternal berawalan \"http\" atau \"https\" ke website lain selain format link internal toko di atas.
- Jika user meminta email support, katakan: \"Saat ini dukungan support dapat diakses langsung melalui menu Tiket Bantuan di akun website AM Merchandise kamu.\"";

            $systemPromptContent = $baseSystemPrompt . $productKnowledge;

            $systemPrompt = [
                'role' => 'system',
                'content' => $systemPromptContent
            ];

            // Attach system prompt at the beginning of message history
            if (empty($messages)) {
                $messages[] = $systemPrompt;
            } else {
                if ($messages[0]['role'] === 'system') {
                    $messages[0] = $systemPrompt;
                } else {
                    array_unshift($messages, $systemPrompt);
                }
            }

            // Limit message history size
            $messages = array_slice($messages, -10);

            /*
            |--------------------------------------------------------------------------
            | REQUEST KE MISTRAL AI (USING DATABASE CONFIGS)
            |--------------------------------------------------------------------------
            */
            $apiKey = Setting::get('mistral_api_key') ?: env('MISTRAL_API_KEY');
            $model = Setting::get('mistral_model', 'open-mistral-7b');

            if (empty($apiKey)) {
                return response()->json([
                    'reply' => '⚠️ Mistral API Key belum diatur di dashboard admin. Silakan atur terlebih dahulu untuk menggunakan asisten AI.'
                ]);
            }

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    'https://api.mistral.ai/v1/chat/completions',
                    [
                        'model' => $model,
                        'temperature' => 0.0,
                        'max_tokens' => 500,
                        'messages' => $messages
                    ]
                );

            /*
            |--------------------------------------------------------------------------
            | RESPONSE AI
            |--------------------------------------------------------------------------
            */
            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? $response->body() ?? 'Unknown API Error';
                
                if (config('app.debug')) {
                    $reply = '⚠️ API Error: ' . $errorMessage;
                } else {
                    $reply = 'Maaf, sepertinya saya sedang kelelahan. Bisa tanya lagi sebentar lagi? 🙏';
                }
            } else {
                $reply = $response['choices'][0]['message']['content']
                    ?? 'Maaf, sepertinya saya sedang kelelahan. Bisa tanya lagi sebentar lagi? 🙏';
            }

            return response()->json([
                'reply' => $reply
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}