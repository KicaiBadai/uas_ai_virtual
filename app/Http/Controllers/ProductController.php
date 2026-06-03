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
}
