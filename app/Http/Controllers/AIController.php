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
| 1. ALLOW CHAT NORMAL (GREETING / OBROLAN UMUM)
|--------------------------------------------------------------------------
*/

$normalChats = [
    'halo', 'hai', 'hi', 'pagi', 'siang', 'malam',
    'apa kabar', 'kabar', 'assalamualaikum', 'tes'
];

foreach ($normalChats as $chat) {
    if (str_contains($message, $chat)) {
        // lanjut ke AI tanpa blok
        $isOutOfScope = false;
        goto continue_process;
    }
}

/*
|--------------------------------------------------------------------------
| 2. CEK TOPIK TOKO
|--------------------------------------------------------------------------
*/

$allowedKeywords = [
    'hoodie', 'kaos', 'topi', 'merchandise',
    'harga', 'pengiriman', 'toko', 'produk',
    'anime', 'gaming', 'streetwear'
];

$isOutOfScope = true;

foreach ($allowedKeywords as $keyword) {
    if (str_contains($message, $keyword)) {
        $isOutOfScope = false;
        break;
    }
}

if ($isOutOfScope) {
    return response()->json([
        'reply' => 'Saya hanya bisa membantu seputar produk dan AM Merchandise.'
    ]);
}

continue_process:


            /*
            |--------------------------------------------------------------------------
            | FAQ MANUAL
            |--------------------------------------------------------------------------
            */

            $faq = [

                'jam buka' =>
                'Toko buka setiap hari jam 08:00 sampai 22:00.',

                'pengiriman' =>
                'Pengiriman memakan waktu sekitar 2-3 hari.',

                'harga hoodie' =>
                'Harga hoodie mulai dari Rp180.000.',

                'harga kaos' =>
                'Harga kaos mulai dari Rp95.000.',

                'siapa pembuat website ini' =>
                'Website ini dibuat oleh Aditya Maula.',

                'nama toko' =>
                'Nama toko ini adalah AM Merchandise.',

                'ada hoodie' =>
                'Ya, kami menyediakan hoodie anime dan gaming.',

                'ada topi' =>
                'Ya, tersedia topi streetwear.',

            ];


            /*
            |--------------------------------------------------------------------------
            | CEK FAQ DULU
            |--------------------------------------------------------------------------
            */

            foreach ($faq as $question => $answer) {
                if (str_contains($message, $question)) {
                    return response()->json([
                        'reply' => $answer
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | MEMORY CHAT SESSION
            |--------------------------------------------------------------------------
            */

            $messages = session()->get('chat_history', []);


            /*
            |--------------------------------------------------------------------------
            | SYSTEM PROMPT (DIPERKUAT)
            |--------------------------------------------------------------------------
            */

            if (empty($messages)) {
                $messages[] = [
                    'role' => 'system',
                    'content' => '

Kamu adalah AI customer service AM Merchandise.

ATURAN WAJIB:
- HANYA boleh menjawab tentang produk AM Merchandise
- Jika di luar topik, jawab: "Saya hanya bisa membantu seputar produk AM Merchandise."
- Jawaban singkat (1–2 kalimat)
- Bahasa santai

PRODUK:
- Hoodie anime
- Kaos gaming
- Topi streetwear

INFO:
- Pengiriman: 2–3 hari
- Harga mulai: Rp80.000

'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | TAMBAH PESAN USER
            |--------------------------------------------------------------------------
            */

            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];


            /*
            |--------------------------------------------------------------------------
            | BATASI MEMORY
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
                        'temperature' => 0.5,
                        'max_tokens' => 150,
                        'messages' => $messages
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | AMBIL RESPONSE AI
            |--------------------------------------------------------------------------
            */

            $reply = $response['choices'][0]['message']['content']
                ?? 'AI tidak merespon';


            /*
            |--------------------------------------------------------------------------
            | SIMPAN KE SESSION
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
            | RESPONSE KE FRONTEND
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