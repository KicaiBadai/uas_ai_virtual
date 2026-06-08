<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $productsCollection = Product::all();
        
        // Key by slug to maintain array access like before
        $products = $productsCollection->keyBy('slug')->toArray();
        
        // Define popular products, fallback to first items if not found
        $popularProducts = [
            $products['hoodie-aot'] ?? ($productsCollection->first() ? $productsCollection->first()->toArray() : null),
            $products['kaos-solo-leveling'] ?? ($productsCollection->skip(1)->first() ? $productsCollection->skip(1)->first()->toArray() : null),
            $products['topi-streetwear'] ?? ($productsCollection->skip(2)->first() ? $productsCollection->skip(2)->first()->toArray() : null)
        ];

        // Filter out null values in case database is completely empty
        $popularProducts = array_filter($popularProducts);
        
        return view('chat', compact('products', 'popularProducts'));
    }

    public function show($slug)
    {
        $productModel = Product::where('slug', $slug)->firstOrFail();
        $product = $productModel->toArray();
        
        // Get recommendations from same category
        $otherProductsModels = Product::where('category', $productModel->category)
            ->where('slug', '!=', $slug)
            ->take(2)
            ->get();
            
        // Fallback to other categories if not enough recommendations
        if ($otherProductsModels->count() < 2) {
            $filler = Product::where('slug', '!=', $slug)
                ->take(2 - $otherProductsModels->count())
                ->get();
            $otherProductsModels = $otherProductsModels->concat($filler);
        }

        $otherProducts = $otherProductsModels->toArray();

        return view('product', compact('product', 'otherProducts'));
    }

    public function checkout($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail()->toArray();
        return view('checkout', compact('product'));
    }

    public function checkoutSuccess()
    {
        return view('checkout_success');
    }

    public function processCheckout(Request $request, $slug)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'size' => 'required|string|max:50',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
            'courier' => 'required|string|max:50',
            'payment_method' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();
        $qty = $request->input('qty');

        if ($product->stock < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi. Sisa stok: ' . $product->stock . ' pcs.',
            ], 400);
        }

        // Calculate shipping cost based on courier
        $courier = $request->input('courier');
        $shippingCost = 15000; // Default JNE
        if ($courier === 'J&T') {
            $shippingCost = 22000;
        } elseif ($courier === 'Sicepat') {
            $shippingCost = 10000;
        }

        $totalPrice = ($product->price_raw * $qty) + $shippingCost;

        // Generate unique receipt number starting with AM
        $receiptNumber = '';
        do {
            $receiptNumber = 'AM' . mt_rand(10000, 99999);
        } while (\App\Models\Order::where('receipt_number', $receiptNumber)->exists());

        // Deduct stock
        $product->stock -= $qty;
        $product->save();

        // Create order
        $order = \App\Models\Order::create([
            'receipt_number' => $receiptNumber,
            'customer_name' => $request->input('customer_name'),
            'customer_whatsapp' => $request->input('customer_phone'),
            'customer_address' => $request->input('shipping_address'),
            'product_id' => $product->id,
            'quantity' => $qty,
            'size' => $request->input('size'),
            'shipping_cost' => $shippingCost,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_method' => $request->input('payment_method'),
            'courier' => $courier,
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'order' => [
                'receipt_number' => $order->receipt_number,
                'customer_name' => $order->customer_name,
                'customer_whatsapp' => $order->customer_whatsapp,
                'customer_address' => $order->customer_address,
                'product_id' => $order->product_id,
                'product_name' => $product->name,
                'product_image' => $product->image,
                'quantity' => $order->quantity,
                'size' => $order->size,
                'shipping_cost' => $order->shipping_cost,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'courier' => $order->courier,
                'notes' => $order->notes ?? '-',
                'created_at' => $order->created_at->format('d M Y H:i'),
            ]
        ]);
    }
}
