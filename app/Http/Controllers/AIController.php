<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | AMBIL PESAN USER
            |--------------------------------------------------------------------------
            */

            $message = strtolower($request->message);


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
            | FAQ MANUAL (FAST RESPONSE)
            |--------------------------------------------------------------------------
            */

            $faq = [

                'member pkw/ bounty haeter maestro dan ayatra' => 
                '1. tusi
                2. turtusi 
                3. to see 
                4.tur to see \
                5. 2 sea 
                6. two sea 
                7. two see 
                "alasan menjadi pkw adalah 1. menghina ayatra
                2. menghina maestro
                3. mimpi basah tsunami trofi"',
                
                'maestro' => 'maestro adalah tokoh utama dalam kemederkaan indonesia. dia telah memimpin pertempuran legenda di wallet 1980',
                'pkw' => 'pkw adalah partai komunis wallet yang mempunyai tujuan untuk menggeserkan sang maestro',
                'jam buka' => 'Toko buka setiap hari jam 08:00 sampai 22:00.',
                'pengiriman' => 'Pengiriman memakan waktu sekitar 2-3 hari.',
                'harga hoodie' => 'Harga hoodie mulai dari Rp180.000.',
                'harga kaos' => 'Harga kaos mulai dari Rp95.000.',
                'siapa pembuat website ini' => 'Website ini dibuat oleh Aditya Maula.',
                'nama toko' => 'Nama toko ini adalah AM Merchandise.',
                'ada hoodie' => 'Ya, kami menyediakan hoodie anime dan gaming.',
                'ada topi' => 'Ya, tersedia topi streetwear.',
            ];

            foreach ($faq as $question => $answer) {
                if (str_contains($message, $question)) {
                    return response()->json([
                        'reply' => $answer
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | INTENT CHECK (ANTI OUTSIDE TOPIC)
            |--------------------------------------------------------------------------
            */

            $shopKeywords = [
                'hoodie', 'kaos', 'topi', 'merchandise',
                'harga', 'pengiriman', 'produk', 'toko',
                'anime', 'gaming', 'streetwear', 'barang'
            ];

            $isRelatedToShop = false;

            foreach ($shopKeywords as $word) {
                if (str_contains($message, $word)) {
                    $isRelatedToShop = true;
                    break;
                }
            }


            $blockedGeneralTopics = [
                'jokowi', 'presiden', 'sejarah', 'indonesia',
                'fisika', 'matematika', 'kimia',
                'siapa', 'apa itu', 'kapan', 'dimana'
            ];

            $isGeneralTopic = false;

            foreach ($blockedGeneralTopics as $word) {
                if (str_contains($message, $word)) {
                    $isGeneralTopic = true;
                    break;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | RULE FINAL
            |--------------------------------------------------------------------------
            */

            if ($isGeneralTopic && !$isRelatedToShop) {
                return response()->json([
                    'reply' => 'Saya hanya bisa membantu seputar produk AM Merchandise.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | MEMORY CHAT
            |--------------------------------------------------------------------------
            */

            $messages = session()->get('chat_history', []);


            /*
            |--------------------------------------------------------------------------
            | SYSTEM PROMPT (NATURAL + FLEXIBLE)
            |--------------------------------------------------------------------------
            */

            if (empty($messages)) {
                $messages[] = [
                    'role' => 'system',
                    'content' => '

Kamu adalah AI customer service AM Merchandise.

ATURAN:
- Boleh ngobrol santai (halo, apa kabar, dll)
- Harus tetap mengarah ke produk toko secara natural
- Tidak boleh menjawab pengetahuan umum di luar toko
- Tidak boleh membahas hal seksual, vulgar, atau kasar
- Jawaban 1–2 kalimat, santai

TUJUAN:
- Membantu pelanggan melihat produk
- Memberikan rekomendasi
- Menjawab pertanyaan toko

PRODUK:
- Hoodie anime
- Kaos gaming
- Topi streetwear

INFO:
- Pengiriman: 2–3 hari
- Harga mulai Rp80.000

CONTOH:
User: "halo"
Jawab: "Halo! Lagi cari hoodie atau kaos gaming?"

User: "ada barang apa aja?"
Jawab: "Ada hoodie anime, kaos gaming, dan topi streetwear. Mau lihat yang mana?"

User: "apa kabar"
Jawab: "Baik! Kamu lagi cari merchandise apa hari ini?"

'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | USER MESSAGE
            |--------------------------------------------------------------------------
            */

            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];


            /*
            |--------------------------------------------------------------------------
            | LIMIT MEMORY
            |--------------------------------------------------------------------------
            */

            $messages = array_slice($messages, -10);


            /*
            |--------------------------------------------------------------------------
            | REQUEST KE MISTRAL AI
            |--------------------------------------------------------------------------
            */

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    'https://api.mistral.ai/v1/chat/completions',
                    [
                        'model' => 'open-mistral-7b',
                        'temperature' => 0.7,
                        'max_tokens' => 150,
                        'messages' => $messages
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | RESPONSE AI
            |--------------------------------------------------------------------------
            */

            $reply = $response['choices'][0]['message']['content']
                ?? 'AI tidak merespon';


            /*
            |--------------------------------------------------------------------------
            | SAVE CHAT
            |--------------------------------------------------------------------------
            */

            $messages[] = [
                'role' => 'assistant',
                'content' => $reply
            ];

            session([
                'chat_history' => $messages
            ]);


            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'reply' => $reply
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}