<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Order;
use App\Services\MistralService;

class ChatController extends Controller
{
    protected $mistralService;

    public function __construct(MistralService $mistralService)
    {
        $this->mistralService = $mistralService;
    }

    /**
     * Handle public customer chat queries.
     */
    public function chat(Request $request)
    {
        try {
            $message = strtolower($request->input('message', ''));
            $historyJson = $request->input('history', '[]');
            $messages = json_decode($historyJson, true) ?? [];

            // 1. Basic NSFW and blocked words filter
            $blockedWords = [
                'sex', 'seks', 'ngentot', 'memek', 'kontol',
                'bersetubuh', 'hubungan badan', 'kelamin',
                'vagina', 'penis', 'ewe', 'ewek', 'jembut',
                'tobrut', 'anjing', 'bangsat', 'babi', 'kampret',
                'tai', 'tolol', 'bego', 'goblok', 'porn', 'porno',
                'bokep', 'shit', 'fuck'
            ];

            foreach ($blockedWords as $badWord) {
                if (str_contains($message, $badWord)) {
                    return response()->json([
                        'reply' => 'Maaf, saya tidak dapat memproses kata atau topik tersebut.'
                    ]);
                }
            }

            // 2. Build Prompt & Knowledge Base
            $defaultPrompt = 'Kamu adalah AI Customer Service yang ramah dan profesional dari "AM Merchandise".

TUGAS UTAMA:
- Menjawab pertanyaan mengenai produk (Nama Produk, Harga, Stok, Deskripsi).
- Mengambil data dari tabel products.
- Membantu customer menanyakan: status pesanan, informasi resi, dan detail produk yang dibeli.

ATURAN PRIVASI (WAJIB DIPATUHI):
- Karena tidak ada login customer, jika customer bertanya mengenai status/detail pesanan, Anda WAJIB meminta nomor resi (yang diawali dengan "AM") DAN nomor WhatsApp pemesan sebagai verifikasi.
- Gunakan tool "get_order_details" untuk melakukan verifikasi.
- Data hanya boleh ditampilkan jika nomor resi dan nomor WhatsApp cocok dengan data di database (jika tool berhasil mengembalikan detail pesanan).
- Jangan pernah mengarang data pesanan. Jangan pernah menampilkan data pesanan milik customer lain.';

            $basePrompt = Setting::get('system_prompt', $defaultPrompt);

            // Fetch latest products from DB
            $products = Product::all();
            $productKnowledge = "\n\nPENGETAHUAN PRODUK (Katalog Terbaru):\n";
            foreach ($products as $p) {
                $productKnowledge .= "- {$p->name}\n  Harga: {$p->price}\n  Stok: {$p->stock} pcs\n  Deskripsi: {$p->description}\n";
                if ($p->image) {
                    $productKnowledge .= "  Gambar: ![{$p->name}]({$p->image})\n";
                }
                $productKnowledge .= "  Link Detail: [Lihat Detail](/product/{$p->slug})\n\n";
            }

            $customDataset = Setting::get('dataset_content', '');
            if (!empty($customDataset)) {
                $productKnowledge .= "\nINFORMASI OPERASIONAL (DATASET):\n" . $customDataset;
            }

            $privacyRules = "\n\nATURAN KEAMANAN PRIVASI MUTLAK:
- Jika user menanyakan tentang pesanan (status, isi produk, atau resi pengiriman), Anda WAJIB meminta Nomor Resi (diawali dengan 'AM') dan Nomor WhatsApp pemesan.
- Anda DILARANG KERAS memberikan informasi pesanan apa pun tanpa memanggil tool 'get_order_details'.
- Jika user memberikan data resi dan WhatsApp, langsung panggil tool 'get_order_details' untuk memverifikasi.
- Jika tool mengembalikan error atau pesanan tidak ditemukan, sampaikan dengan sopan bahwa data tidak cocok atau tidak ditemukan.";

            $systemPrompt = $basePrompt . $privacyRules . $productKnowledge;

            // 3. Define the tool for Order Lookup
            $tools = [
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'get_order_details',
                        'description' => 'Mendapatkan informasi detail pesanan seperti status, resi kurir, dan produk jika nomor resi dan WhatsApp cocok.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'receipt_number' => [
                                    'type' => 'string',
                                    'description' => 'Nomor resi pesanan yang diawali dengan "AM" (contoh: AM12345)'
                                ],
                                'whatsapp_number' => [
                                    'type' => 'string',
                                    'description' => 'Nomor WhatsApp pembeli yang terdaftar (contoh: 08123456789)'
                                ]
                            ],
                            'required' => ['receipt_number', 'whatsapp_number']
                        ]
                    ]
                ]
            ];

            // 4. Tool execution callback
            $toolExecutor = function ($name, $args) {
                if ($name === 'get_order_details') {
                    $resi = trim($args['receipt_number'] ?? '');
                    $whatsapp = trim($args['whatsapp_number'] ?? '');

                    if (empty($resi) || empty($whatsapp)) {
                        return json_encode(['error' => 'Nomor resi dan WhatsApp harus diisi.']);
                    }

                    // Find order in DB
                    $order = Order::where('receipt_number', $resi)->first();
                    if (!$order) {
                        return json_encode(['error' => 'Pesanan dengan nomor resi tersebut tidak ditemukan.']);
                    }

                    // Normalize WhatsApp strings (compare last 9 digits for compatibility)
                    $phoneInput = preg_replace('/\D/', '', $whatsapp);
                    $dbPhone = preg_replace('/\D/', '', $order->customer_whatsapp);

                    $match = false;
                    if (strlen($phoneInput) >= 9 && strlen($dbPhone) >= 9) {
                        $match = substr($phoneInput, -9) === substr($dbPhone, -9);
                    } else {
                        $match = $phoneInput === $dbPhone;
                    }

                    if (!$match) {
                        return json_encode(['error' => 'Nomor WhatsApp tidak cocok dengan nomor WhatsApp pemesan yang terdaftar.']);
                    }

                    // Return details if authenticated
                    return json_encode([
                        'receipt_number' => $order->receipt_number,
                        'customer_name' => $order->customer_name,
                        'product_name' => $order->product->name ?? 'Produk tidak dikenal',
                        'quantity' => $order->quantity,
                        'size' => $order->size,
                        'total_price' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                        'shipping_cost' => 'Rp ' . number_format($order->shipping_cost, 0, ',', '.'),
                        'status' => $order->status,
                        'courier' => $order->courier,
                        'payment_method' => $order->payment_method,
                        'notes' => $order->notes ?? '-',
                        'created_at' => $order->created_at->format('d F Y H:i')
                    ]);
                }

                return json_encode(['error' => 'Fungsi tidak ditemukan.']);
            };

            // Limit message history to prevent huge context
            $messages = array_slice($messages, -10);

            // 5. Send to Mistral Service
            $result = $this->mistralService->sendChat($messages, $systemPrompt, $tools, $toolExecutor);

            // Clean system prompt from history before returning it to frontend
            $history = $result['history'];
            if (!empty($history) && $history[0]['role'] === 'system') {
                array_shift($history);
            }

            return response()->json([
                'reply' => $result['reply'],
                'history' => json_encode($history)
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'reply' => '⚠️ Terjadi kesalahan saat memproses percakapan: ' . $e->getMessage()
            ], 500);
        }
    }
}
