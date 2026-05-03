<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        try {

            // AMBIL PESAN USER
            $message = strtolower($request->message);



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

            foreach ($faq as $question => $answer)
            {
                if (str_contains($message, $question))
                {
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

            $messages =
            session()->get('chat_history', []);



            /*
            |--------------------------------------------------------------------------
            | SYSTEM PROMPT
            |--------------------------------------------------------------------------
            */

            if (empty($messages))
            {

                $messages[] = [

                    'role' => 'system',

                    'content' => '

                    Kamu adalah customer service AI toko merchandise.

                    ATURAN WAJIB:
                    - Jawab singkat, jelas, dan mudah dipahami
                    - Maksimal 1–2 kalimat
                    - Tidak perlu penjelasan panjang
                    - Gunakan bahasa santai
                    - Jangan gunakan emoji berlebihan
                    - Fokus hanya pada merchandise

                    CARA JAWAB:
                    - Langsung ke inti jawaban
                    - Tidak perlu cerita tambahan
                    - Tidak perlu penjelasan detail kecuali diminta

                    Produk:
                    - Hoodie anime
                    - Kaos gaming
                    - Topi streetwear

                    Informasi toko:
                    - Nama: AM Merchandise
                    - Dibuat oleh: Aditya Maula
                    - Pengiriman: 2–3 hari
                    - Harga mulai: Rp80.000

                    Jika di luar topik merchandise, jawab:
                    "Topik di luar pemahaman saya."

                    '

                ];
            }



            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN CHAT USER
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

                    'Authorization' =>
                    'Bearer ' . env('MISTRAL_API_KEY'),

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
            | AMBIL BALASAN AI
            |--------------------------------------------------------------------------
            */

            $reply =

            $response['choices'][0]['message']['content']

            ?? 'AI tidak merespon';



            /*
            |--------------------------------------------------------------------------
            | SIMPAN BALASAN AI KE MEMORY
            |--------------------------------------------------------------------------
            */

            $messages[] = [

                'role' => 'assistant',

                'content' => $reply

            ];



            /*
            |--------------------------------------------------------------------------
            | SIMPAN SESSION
            |--------------------------------------------------------------------------
            */

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