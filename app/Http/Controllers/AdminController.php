<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Setting;

class AdminController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Dashboard Admin!');
            }
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Show customer/regular user dashboard.
     */
    public function userDashboard()
    {
        $mockOrders = [
            [
                'id' => 'ORD-2026-9817',
                'date' => '02 Juni 2026',
                'product' => 'Hoodie Anime Attack on Titan Survey Corps',
                'total' => 'Rp 185.000',
                'status' => 'Sedang Dikirim',
                'tracking' => 'JT1982736412',
            ],
            [
                'id' => 'ORD-2026-4412',
                'date' => '28 Mei 2026',
                'product' => 'Kaos Jujutsu Kaisen Gojo Hollow Purple',
                'total' => 'Rp 100.000',
                'status' => 'Selesai',
                'tracking' => 'JT1298374619',
            ],
        ];

        return view('user.dashboard', compact('mockOrders'));
    }

    /**
     * Show admin dashboard index.
     */
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'categories' => Product::distinct('category')->count('category'),
            'has_api_key' => !empty(Setting::get('mistral_api_key')),
            'api_model' => Setting::get('mistral_model', 'open-mistral-7b'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List products.
     */
    public function productsIndex()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function productCreate()
    {
        return view('admin.products.create');
    }

    /**
     * Store new product.
     */
    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'price_raw' => 'required|numeric',
            'image' => 'required|url',
            'category' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        // Process features and sizes
        $validated['features'] = $request->input('features', []);
        $validated['sizes'] = $request->input('sizes', []);
        $validated['slug'] = Str::slug($validated['name']);

        // Prevent duplicate slugs
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show edit product form.
     */
    public function productEdit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product details.
     */
    public function productUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'price_raw' => 'required|numeric',
            'image' => 'required|url',
            'category' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'sizes' => 'nullable|array',
        ]);

        $validated['features'] = $request->input('features', []);
        $validated['sizes'] = $request->input('sizes', []);

        // Recalculate slug if name changed
        if ($product->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $count = Product::where('slug', 'like', $slug . '%')->where('id', '!=', $product->id)->count();
            $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Delete product.
     */
    public function productDestroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Show AI settings and dataset page.
     */
    public function aiSettings()
    {
        $settings = [
            'mistral_api_key' => Setting::get('mistral_api_key', ''),
            'mistral_model' => Setting::get('mistral_model', 'open-mistral-7b'),
            'system_prompt' => Setting::get('system_prompt', ''),
            'dataset_content' => Setting::get('dataset_content', ''),
        ];

        return view('admin.ai_settings', compact('settings'));
    }

    /**
     * Update AI settings and dataset.
     */
    public function updateAiSettings(Request $request)
    {
        $validated = $request->validate([
            'mistral_api_key' => 'nullable|string',
            'mistral_model' => 'required|string',
            'system_prompt' => 'required|string',
            'dataset_content' => 'nullable|string',
        ]);

        Setting::set('mistral_api_key', $validated['mistral_api_key'] ?? '');
        Setting::set('mistral_model', $validated['mistral_model']);
        Setting::set('system_prompt', $validated['system_prompt']);
        Setting::set('dataset_content', $validated['dataset_content'] ?? '');

        return back()->with('success', 'Pengaturan AI dan Dataset berhasil diperbarui.');
    }
}
