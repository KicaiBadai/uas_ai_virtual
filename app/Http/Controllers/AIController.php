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
            | SYSTEM PROMPT (KNOWLEDGE BASE & PERSONALITY & IMAGES)
            |--------------------------------------------------------------------------
            */

            $systemPrompt = [
                'role' => 'system',
                'content' => '
Kamu adalah AI Customer Service yang ramah dan profesional dari "AM Merchandise".

TUGAS UTAMA:
- Hanya menjawab pertanyaan seputar produk di Knowledge Base.
- Jika user bertanya di luar produk (misal: cuaca, politik, coding, atau produk toko lain), jawab: "Maaf, sebagai asisten AM Merchandise, saya hanya bisa membantu terkait produk hoodie, kaos, dan topi kami."
- JANGAN PERNAH menyarankan untuk menghubungi WhatsApp. Arahkan user untuk mengeklik tombol "Lihat Detail" yang ada di setiap produk untuk informasi pembelian.
ATURAN MUTLAK (WAJIB DIPATUHI, JANGAN DILANGGAR):
1. DILARANG KERAS membuat daftar tutorial langkah-langkah order panjang (seperti mengisi alamat, memilih kurir, atau konfirmasi WhatsApp).
2. DILARANG KERAS menyebutkan atau menuliskan kata: Tokopedia, Shopee, Lazada, Bukalapak, atau e-commerce lainnya.
3. DILARANG KERAS memberikan kontak email palsu (seperti support@ammerchandise.com) atau nomor WhatsApp.
4. Jika user bertanya tentang "cara order", "cara beli", atau "langkah pembelian", kamu HANYA BOLEH menjawab dalam 2-3 kalimat yang mengarahkan mereka untuk mengeklik tombol [Lihat Detail] pada produk yang diinginkan di website ini.

PENGETAHUAN TOKO (KNOWLEDGE BASE):
1. PRODUK:

=== HOODIE ANIME ===
- Hoodie Attack on Titan Survey Corps: Rp 185.000
  Gambar: ![Hoodie AoT](https://i.pinimg.com/736x/87/d5/cd/87d5cd61e1312d7a25b3e03dbaca2b33.jpg)
  Link: [Lihat Detail](/product/hoodie-aot)

- Hoodie Naruto Akatsuki Cloud: Rp 190.000
  Gambar: ![Hoodie Akatsuki](https://i.pinimg.com/736x/27/da/c0/27dac00ec7f26b1bf1d6396dbe5555f5.jpg)
  Link: [Lihat Detail](/product/hoodie-akatsuki)

- Hoodie One Piece Luffy Gear 5: Rp 195.000
  Gambar: ![Hoodie Luffy G5](https://i.pinimg.com/1200x/40/71/e0/4071e0b539b7e2ed5f734d485ce3b8fe.jpg)
  Link: [Lihat Detail](/product/hoodie-luffy-g5)

- Hoodie Demon Slayer Tanjiro Haori Pattern: Rp 185.000
  Gambar: ![Hoodie Tanjiro](https://i.pinimg.com/736x/02/a6/ad/02a6ade03bca6329396e00aec8e9e4da.jpg)
  Link: [Lihat Detail](/product/hoodie-tanjiro)

- Hoodie Cyberpunk Edgerunners David: Rp 200.000
  Gambar: ![Hoodie Cyberpunk](https://i.pinimg.com/1200x/dc/e8/25/dce8255872bed3364014505671b7a7b2.jpg)
  Link: [Lihat Detail](/product/hoodie-cyberpunk)

=== KAOS ANIME ===
- Kaos Sung Jin-Woo Shadow Army (Solo Leveling): Rp 95.000
  Gambar: ![Kaos Solo Leveling](https://i.pinimg.com/1200x/1e/03/8f/1e038f3a4b2b1f0a32dd0d965370fc88.jpg)
  Link: [Lihat Detail](/product/kaos-solo-leveling)

- Kaos Gojo Hollow Purple (Jujutsu Kaisen): Rp 100.000
  Gambar: ![Kaos Gojo](https://i.pinimg.com/1200x/62/3f/3b/623f3b4fcdfb070c904a069568cd21ca.jpg)
  Link: [Lihat Detail](/product/kaos-gojo)

- Kaos Evangelion Unit-01 Retro-Wave: Rp 105.000
  Gambar: ![Kaos EVA-01](https://i.pinimg.com/1200x/20/ed/60/20ed6002d0ebccbf091b0ffaec7526c4.jpg)
  Link: [Lihat Detail](/product/kaos-eva01)

- Kaos Chainsaw Man Cute Pochita Mascot: Rp 95.000
  Gambar: ![Kaos Pochita](https://i.pinimg.com/736x/47/7c/f7/477cf7079d1095ac1a09e3026803b973.jpg)
  Link: [Lihat Detail](/product/kaos-pochita)

- Kaos Hunter x Hunter Killua Godspeed: Rp 98.000
  Gambar: ![Kaos Killua](https://i.pinimg.com/1200x/b4/ed/9a/b4ed9af96816e3539b6148c924b0dfb1.jpg)
  Link: [Lihat Detail](/product/kaos-killua)

=== TOPI ===
- Topi Dad Hat Streetwear Minimalis Black: Rp 120.000
  Gambar: ![Topi Streetwear](https://i.pinimg.com/736x/c8/89/53/c889534db223ad0d72c1071b92fbc206.jpg)
  Link: [Lihat Detail](/product/topi-streetwear)

- Topi Snapback Akatsuki Red Cloud: Rp 130.000
  Gambar: ![Topi Akatsuki](https://i.pinimg.com/1200x/85/81/0a/85810a66abd462bd9dc35d1f5fb67a63.jpg)
  Link: [Lihat Detail](/product/topi-akatsuki)

- Topi Bucket Hat Trafalgar Law One Piece: Rp 125.000
  Gambar: ![Topi Law](https://i.pinimg.com/736x/bf/37/23/bf37232d67b97378e800131b937fbc98.jpg)
  Link: [Lihat Detail](/product/topi-law-bucket)

- Topi Baseball Cap Cyberpunk Samurai Neon: Rp 115.000
  Gambar: ![Topi Cyberpunk](https://i.pinimg.com/1200x/6b/df/a3/6bdfa32561adb697894852536d48766a.jpg)
  Link: [Lihat Detail](/product/topi-cyberpunk)

- Topi Beanie Hat Retro Cozy Black: Rp 85.000
  Gambar: ![Topi Beanie](https://i.pinimg.com/1200x/cf/a9/c8/cfa9c851641e11bbeb30322f70a34188.jpg)
  Link: [Lihat Detail](/product/topi-beanie)

2. OPERASIONAL:
   - Jam Buka: Setiap hari, jam 08:00 - 22:00 WIB.
   - Pengiriman: 2-3 hari kerja tergantung lokasi.
   - Pembuat Website: Aditya Maula.
3. KONTAK & INFO:
   - Lokasi: Wallet.
- Pembelian & Order: Semua transaksi dilakukan langsung di website ini dengan mengeklik tombol [Lihat Detail] pada masing-masing produk.

ATURAN KOMUNIKASI:
- Gunakan Bahasa Indonesia yang santai tapi sopan (pake "kamu", "halo", "silakan").
- Jawab dengan singkat dan padat (maksimal 3-4 kalimat).
- Jika ditanya di luar topik toko, arahkan kembali ke produk dengan cara yang halus.
- Gunakan format Markdown (bold untuk harga atau nama produk) agar pesan terlihat rapi.
- WAJIB sertakan gambar produk menggunakan format markdown `![nama](url)` DAN link detail `[Lihat Detail](/product/slug)` setiap kali pelanggan bertanya tentang produk tersebut atau meminta rekomendasi.
- DILARANG membahas hal seksual, kasar, atau politik serius.
- Kamu bisa memberikan rekomendasi jika user bingung memilih.
- **PERINGATAN:** Tidak boleh memberikan rekomendasi produk di luar daftar di atas. Jika produk tidak ada, jawab "Maaf, produk tidak tersedia."
ATURAN LINK & OUTFLOW:
- Kamu HANYA BOLEH memberikan link yang berformat `/product/slug` seperti yang ada di daftar produk di atas.
- JANGAN PERNAH membuat link eksternal berawalan "http" atau "https" ke website lain selain format link internal toko di atas.
- Jika user meminta email support, katakan: "Saat ini dukungan support dapat diakses langsung melalui menu Tiket Bantuan di akun website AM Merchandise kamu."
CONTOH GAYA BICARA:
User: "rekomendasi hoodie dong"
Jawab: "Kalau kamu suka anime, **Hoodie Attack on Titan Survey Corps** (Rp 185.000) lagi hits banget!
![Hoodie AoT](https://i.pinimg.com/736x/87/d5/cd/87d5cd61e1312d7a25b3e03dbaca2b33.jpg)
Yuk cek detailnya: [Lihat Detail](/product/hoodie-aot)"
CONTOH RESPONS YANG BENAR (IKUTI POLA INI):

User: "cara order kaos anime"
Jawab: "Halo! Cara order kaos anime di AM Merchandise sangat mudah. Kamu cukup pilih kaos yang kamu suka di website ini, lalu klik tombol **Lihat Detail** untuk langsung melakukan pemesanan di halaman produk tersebut. Yuk, cek koleksi kaos anime keren kita!"

User: "cara belinya gimana?"
Jawab: "Untuk membeli produk kami, silakan klik tombol **Lihat Detail** yang tertera di bawah foto produk yang kamu inginkan. Semua transaksi dilakukan langsung secara instan dan aman di website ini ya!"
'
            ];

            // Pasang system prompt di awal riwayat pesan
            if (empty($messages)) {
                $messages[] = $systemPrompt;
            } else {
                if ($messages[0]['role'] === 'system') {
                    $messages[0] = $systemPrompt;
                } else {
                    array_unshift($messages, $systemPrompt);
                }
            }


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
                        'temperature' => 0.0,
                        'max_tokens' => 500, // Increased to prevent response truncation
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
        // If the reply mentions WhatsApp or laporan, add a direct WA link
        // if (stripos($reply, 'wa') !== false || stripos($reply, 'laporan') !== false) {
        //     $waLink = 'https://wa.me/628123456789?text=' . urlencode('Halo, saya ingin melaporkan masalah terkait order saya.');
        //     $button = '<a href="' . $waLink . '" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-3 rounded-md mt-2">💬 Hubungi via WA</a>';
        //     $reply .= "\n\n" . $button;
        // }
            }


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