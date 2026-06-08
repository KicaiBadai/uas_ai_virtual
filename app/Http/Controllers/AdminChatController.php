<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\AdminChatHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\MistralService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminChatController extends Controller
{
    protected $mistralService;

    public function __construct(MistralService $mistralService)
    {
        $this->mistralService = $mistralService;
    }

    /**
     * Show admin chatbot page.
     */
    public function index()
    {
        return view('admin.chat');
    }

    /**
     * Handle admin chatbot queries.
     */
    public function chat(Request $request)
    {
        try {
            $message = $request->input('message', '');
            $historyJson = $request->input('history', '[]');
            $messages = json_decode($historyJson, true) ?? [];

            // Add new message to history
            $messages[] = ['role' => 'user', 'content' => $message];

            $systemPrompt = "Kamu adalah AI Asisten Admin profesional untuk toko \"AM Merchandise\".

TUGAS UTAMA:
- Membantu Admin dalam memantau, mencari, dan memperbarui status pesanan.
- Membantu Admin dalam mencari dan memperbarui stok produk.
- Menyajikan ringkasan laporan keuangan/penjualan dari database internal secara akurat.

ATURAN PENTING:
1. Hubungkan pencarian pesanan dengan tool \"search_orders\".
2. Hubungkan pembaruan status resi (menjadi pending, diproses, dikirim, atau selesai) dengan tool \"update_order_status\".
3. Hubungkan permintaan ringkasan penjualan, total pesanan hari ini, produk terlaris, atau pesanan yang belum dikirim dengan tool \"get_sales_info\".
4. Untuk mengupdate stok produk, WAJIB gunakan tool \"search_products\" terlebih dahulu untuk mendapatkan product_id, kemudian gunakan \"update_product_stock\".
5. Tampilkan semua data dalam format Markdown yang rapi (gunakan tabel jika memungkinkan).
6. Jangan pernah mengarang informasi jika tidak ditemukan di database.
7. Jika admin minta \"tambah N stok\", cari produknya dulu, lalu hitung stok_lama + N = stok_baru, kemudian update.
8. Jika pertanyaan tidak berhubungan dengan AM Merchandise, jawab: \"Maaf, saya hanya dapat membantu terkait AM Merchandise.\";";

            // Define tools for Admin AI
            $tools = [
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'search_orders',
                        'description' => 'Mencari pesanan berdasarkan kata kunci (nama pembeli, nomor resi, WhatsApp) atau status.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'query' => [
                                    'type' => 'string',
                                    'description' => 'Nama pembeli, nomor resi, atau kontak (contoh: Afnan)'
                                ],
                                'status' => [
                                    'type' => 'string',
                                    'description' => 'Filter status pesanan (contoh: pending, diproses, dikirim, selesai)'
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'update_order_status',
                        'description' => 'Memperbarui status pesanan berdasarkan nomor resi.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'receipt_number' => [
                                    'type' => 'string',
                                    'description' => 'Nomor resi pesanan (contoh: AM12345)'
                                ],
                                'status' => [
                                    'type' => 'string',
                                    'description' => 'Status baru pesanan',
                                    'enum' => ['pending', 'diproses', 'dikirim', 'selesai']
                                ]
                            ],
                            'required' => ['receipt_number', 'status']
                        ]
                    ]
                ],
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'search_products',
                        'description' => 'Mencari produk berdasarkan nama untuk mendapatkan product_id dan stok saat ini. Gunakan tool ini SEBELUM update_product_stock.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'description' => 'Nama produk atau kata kunci (contoh: topi akatsuki, hoodie naruto)'
                                ]
                            ],
                            'required' => ['name']
                        ]
                    ]
                ],
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'update_product_stock',
                        'description' => 'Menambahkan stok produk berdasarkan product_id. Gunakan search_products dulu untuk mendapatkan product_id. Parameter new_stock adalah jumlah yang akan ditambahkan ke stok saat ini.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'product_id' => [
                                    'type' => 'integer',
                                    'description' => 'ID produk yang akan diupdate (didapat dari search_products)'
                                ],
                                'new_stock' => [
                                    'type' => 'integer',
                                    'description' => 'Jumlah stok yang akan ditambahkan ke stok saat ini.'
                                ]
                            ],
                            'required' => ['product_id', 'new_stock']
                        ]
                    ]
                ],
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'get_sales_info',
                        'description' => 'Mendapatkan informasi ringkasan penjualan: total pesanan, produk terlaris, total pendapatan, dll.',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'period' => [
                                    'type' => 'string',
                                    'description' => 'Periode laporan: today, week, month, all',
                                    'enum' => ['today', 'week', 'month', 'all']
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            // Tool executor closure
            $toolExecutor = function ($name, $args) {
                if ($name === 'search_orders') {
                    $query = trim($args['query'] ?? '');
                    $status = trim($args['status'] ?? '');
                    $dbQuery = Order::with('product');

                    if (!empty($query)) {
                        $dbQuery->where(function ($q) use ($query) {
                            $q->where('receipt_number', 'like', "%$query%")
                              ->orWhere('customer_name', 'like', "%$query%")
                              ->orWhere('customer_whatsapp', 'like', "%$query%");
                        });
                    }

                    if (!empty($status)) {
                        $dbQuery->where('status', $status);
                    }

                    $orders = $dbQuery->latest()->limit(15)->get();

                    if ($orders->isEmpty()) {
                        return json_encode(['message' => 'Tidak ada pesanan yang cocok dengan kriteria pencarian.']);
                    }

                    $results = [];
                    foreach ($orders as $order) {
                        $results[] = [
                            'receipt_number'     => $order->receipt_number,
                            'customer_name'      => $order->customer_name,
                            'customer_whatsapp'  => $order->customer_whatsapp,
                            'product_name'       => $order->product->name ?? 'Produk tidak ditemukan',
                            'quantity'           => $order->quantity,
                            'size'               => $order->size,
                            'total_price'        => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                            'status'             => $order->status,
                            'courier'            => $order->courier,
                            'created_at'         => $order->created_at->format('d M Y H:i'),
                        ];
                    }
                    return json_encode(['success' => true, 'orders' => $results]);
                }

                if ($name === 'update_order_status') {
                    $resi      = trim($args['receipt_number'] ?? '');
                    $newStatus = trim($args['status'] ?? '');
                    $order     = Order::where('receipt_number', $resi)->first();
                    if (!$order) {
                        return json_encode(['success' => false, 'error' => "Pesanan dengan resi {$resi} tidak ditemukan."]);
                    }
                    $oldStatus  = $order->status;
                    $order->status = $newStatus;
                    $order->save();
                    return json_encode([
                        'success' => true,
                        'message' => "Berhasil memperbarui status resi {$resi} dari '{$oldStatus}' menjadi '{$newStatus}'."
                    ]);
                }

                if ($name === 'search_products') {
                    $keyword = trim($args['name'] ?? '');
                    if (empty($keyword)) {
                        return json_encode(['success' => false, 'error' => 'Kata kunci pencarian produk tidak boleh kosong.']);
                    }
                    $products = Product::where('name', 'like', "%{$keyword}%")
                        ->select('id', 'name', 'stock', 'price')
                        ->limit(10)
                        ->get();

                    if ($products->isEmpty()) {
                        return json_encode(['success' => false, 'message' => "Produk dengan kata kunci \"{$keyword}\" tidak ditemukan."]);
                    }

                    $results = [];
                    foreach ($products as $p) {
                        $results[] = [
                            'product_id' => $p->id,
                            'name'       => $p->name,
                            'stock'      => $p->stock,
                            'price'      => 'Rp ' . number_format((float)($p->price_raw ?? preg_replace('/[^0-9]/', '', $p->price ?: '0')), 0, ',', '.'),
                        ];
                    }
                    return json_encode(['success' => true, 'products' => $results]);
                }

                if ($name === 'update_product_stock') {
                    $productId = $args['product_id'] ?? null;
                    $addStock = $args['new_stock'] ?? null;
                    if ($productId === null || $addStock === null) {
                        return json_encode(['success' => false, 'error' => 'Parameter product_id dan new_stock wajib diisi.']);
                    }
                    $product = Product::find($productId);
                    if (!$product) {
                        return json_encode(['success' => false, 'error' => "Produk dengan ID {$productId} tidak ditemukan."]);
                    }
                    $oldStock = $product->stock;
                    $product->stock = $oldStock + (int) $addStock;
                    $product->save();
                    return json_encode([
                        'success' => true,
                        'message' => "Stok produk \"{$product->name}\" berhasil diperbarui dari {$oldStock} menjadi {$product->stock}."
                    ]);
                }

                if ($name === 'get_sales_info') {
                    $period = $args['period'] ?? 'all';
                    $query  = Order::with('product');

                    if ($period === 'today') {
                        $query->whereDate('created_at', Carbon::today());
                    } elseif ($period === 'week') {
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    } elseif ($period === 'month') {
                        $query->whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year);
                    }

                    $orders = $query->get();

                    $totalOrders   = $orders->count();
                    $totalRevenue  = $orders->sum('total_price');
                    $byStatus      = $orders->groupBy('status')->map->count();

                    // Top 5 products
                    $topProducts = $orders->groupBy('product_id')->map(function ($group) {
                        return [
                            'name'     => $group->first()->product->name ?? 'Produk dihapus',
                            'quantity' => $group->sum('quantity'),
                            'revenue'  => $group->sum('total_price'),
                        ];
                    })->sortByDesc('quantity')->take(5)->values();

                    return json_encode([
                        'success'      => true,
                        'period'       => $period,
                        'total_orders' => $totalOrders,
                        'total_revenue'=> 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                        'by_status'    => $byStatus,
                        'top_products' => $topProducts,
                    ]);
                }

                // Fallback for unknown tool
                return json_encode(['error' => 'Tool not recognized.']);
            };

            // Append user message to history if not already the last message in history
            $lastMessage = !empty($messages) ? end($messages) : null;
            if (!$lastMessage || ($lastMessage['role'] ?? '') !== 'user' || ($lastMessage['content'] ?? '') !== $message) {
                $messages[] = ['role' => 'user', 'content' => $message];
            }

            // Limit history length to last 10 messages
            $messages = array_slice($messages, -10);
            
            // Send to Mistral AI
            $result = $this->mistralService->sendChat($messages, $systemPrompt, $tools, $toolExecutor);

            // Clean system prompt from returned history
            $history = $result['history'] ?? [];
            // Remove system prompt if present
            if (!empty($history) && $history[0]['role'] === 'system') {
                array_shift($history);
            }
            // Ensure no duplicate user message at the end (same as last sent)
            $lastMsg = end($history);
            if ($lastMsg && $lastMsg['role'] === 'user' && $lastMsg['content'] === $message) {
                array_pop($history);
            }

            // Save chat history for admin if authenticated — append only the new exchange
            if (Auth::check()) {
                try {
                    $adminId = Auth::id();
                    $replyText = $result['reply'] ?? '';

                    // Prevent duplicate entries: check if the latest saved user message matches the current one
                    $lastHistory = AdminChatHistory::where('admin_id', $adminId)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    $isDuplicate = false;
                    if ($lastHistory) {
                        $lastUserMsg = collect($lastHistory->messages)
                            ->firstWhere('role', 'user');
                        if ($lastUserMsg && $lastUserMsg['content'] === $message) {
                            $isDuplicate = true;
                        }
                    }
                    if (!$isDuplicate) {
                        AdminChatHistory::create([
                            'admin_id' => $adminId,
                            'messages' => [
                                ['role' => 'user',      'content' => $message],
                                ['role' => 'assistant', 'content' => $replyText],
                            ]
                        ]);
                    }                } catch (\Throwable $dbEx) {
                    // Table may not exist yet; log and continue gracefully
                    Log::warning('Could not save AdminChatHistory: ' . $dbEx->getMessage());
                }
            }

            return response()->json([
                'reply'   => $result['reply'] ?? '',
                'history' => json_encode($history)
            ]);
        } catch (\Throwable $e) {
            Log::error('AdminChatController error: ' . $e->getMessage());
            return response()->json([
                'reply' => '⚠️ Terjadi kesalahan internal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent chat histories for the logged-in admin.
     */
    public function recentChat(Request $request)
    {
        try {
            $adminId   = Auth::id();
            $histories = AdminChatHistory::where('admin_id', $adminId)
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get()
                ->map(function ($h) {
                    return [
                        'id'         => $h->id,
                        'messages'   => $h->messages,
                        'created_at' => $h->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            return response()->json(['histories' => $histories]);
        } catch (\Throwable $e) {
            return response()->json(['histories' => []]);
        }
    }
}
?>
