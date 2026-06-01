<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products = [
        // ================= HOODIES =================
        'hoodie-aot' => [
            'slug' => 'hoodie-aot',
            'name' => 'Hoodie Anime Attack on Titan Survey Corps',
            'price' => 'Rp 185.000',
            'price_raw' => 185000,
            'image' => 'https://i.pinimg.com/736x/87/d5/cd/87d5cd61e1312d7a25b3e03dbaca2b33.jpg',
            'category' => 'Hoodie',
            'badge' => 'Bestseller',
            'description' => 'Hoodie edisi khusus Attack on Titan dengan lambang Survey Corps (Wings of Freedom) bordir presisi di bagian belakang. Terbuat dari Cotton Fleece tebal 280gsm yang nyaman dan adem.',
            'features' => [
                'Bahan Cotton Fleece 280gsm Premium',
                'Bordir Komputer Wings of Freedom super detail',
                'Kantong Kangaroo depan yang luas',
                'Tali hoodie tebal dengan ujung besi premium'
            ],
            'sizes' => ['M', 'L', 'XL', 'XXL']
        ],
        'hoodie-akatsuki' => [
            'slug' => 'hoodie-akatsuki',
            'name' => 'Hoodie Naruto Akatsuki Cloud',
            'price' => 'Rp 190.000',
            'price_raw' => 190000,
            'image' => 'https://i.pinimg.com/736x/27/da/c0/27dac00ec7f26b1bf1d6396dbe5555f5.jpg',
            'category' => 'Hoodie',
            'badge' => 'Trending',
            'description' => 'Hoodie bertema Akatsuki dengan sablon bordir awan merah ikonik di dada dan lengan. Gaya minimalis streetwear Jepang yang sangat populer.',
            'features' => [
                'Bahan Premium Cotton Fleece',
                'Aplikasi Awan Merah Bordir Satin tebal',
                'Model Oversized Fit ala streetwear modern',
                'Kualitas jahitan double-stitch standar ekspor'
            ],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL']
        ],
        'hoodie-luffy-g5' => [
            'slug' => 'hoodie-luffy-g5',
            'name' => 'Hoodie One Piece Luffy Gear 5',
            'price' => 'Rp 195.000',
            'price_raw' => 195000,
            'image' => 'https://i.pinimg.com/1200x/40/71/e0/4071e0b539b7e2ed5f734d485ce3b8fe.jpg',
            'category' => 'Hoodie',
            'badge' => 'Hot Item',
            'description' => 'Hoodie premium edisi Luffy Gear 5 (Nika) dengan ilustrasi neon keren di bagian belakang. Sangat cocok bagi para fans setia nakama One Piece.',
            'features' => [
                'Bahan Premium Fleece tebal dan lembut',
                'Sablon DTF Premium warna super cerah & tahan lama',
                'Rib elastis anti-melar di lengan dan pinggang',
                'Nyaman digunakan untuk iklim tropis maupun cuaca dingin'
            ],
            'sizes' => ['M', 'L', 'XL', 'XXL']
        ],
        'hoodie-tanjiro' => [
            'slug' => 'hoodie-tanjiro',
            'name' => 'Hoodie Demon Slayer Tanjiro Haori Pattern',
            'price' => 'Rp 185.000',
            'price_raw' => 185000,
            'image' => 'https://i.pinimg.com/736x/02/a6/ad/02a6ade03bca6329396e00aec8e9e4da.jpg',
            'category' => 'Hoodie',
            'badge' => 'New',
            'description' => 'Hoodie kombinasi warna hitam dan pola catur hijau-hitam khas haori Tanjiro Kamado. Simpel namun langsung menarik perhatian.',
            'features' => [
                'Bahan Cotton Fleece kombinasi kain motif catur sublimasi',
                'Sablon karet elastis logo Demon Slayer Corp di dada',
                'Kupluk hoodie berlapis furing kain hitam',
                'Tekstur kain sangat halus tidak berbulu'
            ],
            'sizes' => ['M', 'L', 'XL']
        ],
        'hoodie-cyberpunk' => [
            'slug' => 'hoodie-cyberpunk',
            'name' => 'Hoodie Cyberpunk Edgerunners David jacket',
            'price' => 'Rp 200.000',
            'price_raw' => 200000,
            'image' => 'https://i.pinimg.com/1200x/dc/e8/25/dce8255872bed3364014505671b7a7b2.jpg',
            'category' => 'Hoodie',
            'badge' => 'Exclusive',
            'description' => 'Hoodie Techwear bergaya futuristik terinspirasi dari jaket kuning legendaris David Martinez di Cyberpunk Edgerunners. Dilengkapi strip reflektif cahaya (glow-in-dark).',
            'features' => [
                'Bahan Heavyweight Fleece 330gsm ultra tebal',
                'Strip neon reflektif memantulkan cahaya saat malam',
                'Desain kerah tinggi (high collar) unik',
                'Kantong samping ber-resleting tak terlihat (hidden zip)'
            ],
            'sizes' => ['L', 'XL', 'XXL']
        ],

        // ================= KAOS ANIME =================
        'kaos-solo-leveling' => [
            'slug' => 'kaos-solo-leveling',
            'name' => 'Kaos Anime Sung Jin-Woo Shadow Army',
            'price' => 'Rp 95.000',
            'price_raw' => 95000,
            'image' => 'https://i.pinimg.com/1200x/1e/03/8f/1e038f3a4b2b1f0a32dd0d965370fc88.jpg',
            'category' => 'Kaos',
            'badge' => 'Bestseller',
            'description' => 'Kaos Solo Leveling premium dengan ilustrasi megah Sung Jin-Woo memanggil pasukan bayangannya. Desain gelap misterius yang elegan.',
            'features' => [
                'Bahan Cotton Combed 30s Reaktif Super Soft',
                'Sablon Plastisol ink premium dengan tingkat presisi tinggi',
                'Jahitan pundak rantai rapi khas kaos distro',
                'Kain adem menyerap keringat dengan sangat baik'
            ],
            'sizes' => ['S', 'M', 'L', 'XL']
        ],
        'kaos-gojo' => [
            'slug' => 'kaos-gojo',
            'name' => 'Kaos Jujutsu Kaisen Gojo Hollow Purple',
            'price' => 'Rp 100.000',
            'price_raw' => 100000,
            'image' => 'https://i.pinimg.com/1200x/62/3f/3b/623f3b4fcdfb070c904a069568cd21ca.jpg',
            'category' => 'Kaos',
            'badge' => 'Trending',
            'description' => 'Kaos premium bertema Gojo Satoru sedang merapal teknik pamungkasnya "Hollow Purple" dengan perpaduan warna neon ungu yang eksotis.',
            'features' => [
                'Bahan Cotton Combed 30s premium premium',
                'Sablon DTF kualitas HD tahan cuci mesin',
                'Potongan kaos Regular Fit nyaman',
                'Label leher satin lembut anti-gatal'
            ],
            'sizes' => ['M', 'L', 'XL', 'XXL']
        ],
        'kaos-eva01' => [
            'slug' => 'kaos-eva01',
            'name' => 'Kaos Evangelion Unit-01 Retro-Wave',
            'price' => 'Rp 105.000',
            'price_raw' => 105000,
            'image' => 'https://i.pinimg.com/1200x/20/ed/60/20ed6002d0ebccbf091b0ffaec7526c4.jpg',
            'category' => 'Kaos',
            'badge' => 'Retro Edisi',
            'description' => 'Kaos Evangelion Unit-01 dengan estetika warna retro 80-an (Synthwave). Cocok bagi pecinta anime mecha vintage.',
            'features' => [
                'Bahan Heavyweight Cotton 24s tebal namun jatuh',
                'Sablon discharge menyatu dengan serat kain',
                'Warna ungu neon retro khas mecha EVA-01',
                'Jahitan samping ganda yang kokoh'
            ],
            'sizes' => ['M', 'L', 'XL', 'XXL']
        ],
        'kaos-pochita' => [
            'slug' => 'kaos-pochita',
            'name' => 'Kaos Chainsaw Man Cute Pochita Mascot',
            'price' => 'Rp 95.000',
            'price_raw' => 95000,
            'image' => 'https://i.pinimg.com/736x/47/7c/f7/477cf7079d1095ac1a09e3026803b973.jpg',
            'category' => 'Kaos',
            'badge' => 'Cute Mascot',
            'description' => 'Kaos Chainsaw Man imut bergambar maskot Pochita bersablon timbul bertekstur beludru (flocking). Lucu dan menggemaskan.',
            'features' => [
                'Bahan Cotton Combed 30s Premium',
                'Sablon Flock Timbul lembut dipegang',
                'Desain minimalis di dada tengah',
                'Unisex cocok untuk pria maupun wanita'
            ],
            'sizes' => ['S', 'M', 'L', 'XL']
        ],
        'kaos-killua' => [
            'slug' => 'kaos-killua',
            'name' => 'Kaos Hunter x Hunter Killua Godspeed',
            'price' => 'Rp 98.000',
            'price_raw' => 98000,
            'image' => 'https://i.pinimg.com/1200x/b4/ed/9a/b4ed9af96816e3539b6148c924b0dfb1.jpg',
            'category' => 'Kaos',
            'badge' => 'New',
            'description' => 'Kaos Killua Zoldyck mengaktifkan mode petirnya "Godspeed". Efek percikan listrik glow-in-dark menambah kesan eksklusif.',
            'features' => [
                'Bahan Cotton Combed 30s Premium',
                'Sablon tinta khusus menyala dalam gelap (glow-in-dark)',
                'Jahitan rantai 3 jarum sangat kuat',
                'Bahan dingin tidak panas dipakai siang hari'
            ],
            'sizes' => ['M', 'L', 'XL']
        ],

        // ================= TOPI =================
        'topi-streetwear' => [
            'slug' => 'topi-streetwear',
            'name' => 'Topi Dad Hat Streetwear Minimalis Black',
            'price' => 'Rp 120.000',
            'price_raw' => 120000,
            'image' => 'https://i.pinimg.com/736x/c8/89/53/c889534db223ad0d72c1071b92fbc206.jpg',
            'category' => 'Topi',
            'badge' => 'Bestseller',
            'description' => 'Topi polo/dad hat katun twill premium dengan bordir logo minimalis putih di depan. Aksesoris esensial penunjang gaya kasual harian Anda.',
            'features' => [
                'Bahan Premium Cotton Twill tebal lembut',
                'Bordir komputer 3D presisi tinggi',
                'Pengatur ukuran gesper besi logam anti karat',
                'Lidah topi melengkung natural (Curved Cap)'
            ],
            'sizes' => ['All Size (Adjustable)']
        ],
        'topi-akatsuki' => [
            'slug' => 'topi-akatsuki',
            'name' => 'Topi Snapback Akatsuki Red Cloud',
            'price' => 'Rp 130.000',
            'price_raw' => 130000,
            'image' => 'https://i.pinimg.com/1200x/85/81/0a/85810a66abd462bd9dc35d1f5fb67a63.jpg',
            'category' => 'Topi',
            'badge' => 'Trending',
            'description' => 'Topi gaya snapback dengan lidah datar kokoh bermotif awan merah Akatsuki di panel depan. Sangat sporty dan keren.',
            'features' => [
                'Bahan Polyester Canvas premium keras kokoh',
                'Aplikasi awan merah border emboss tebal',
                'Pengatur ukuran snapback plastik klasik',
                'Lidah datar (Flat Visor) trendy'
            ],
            'sizes' => ['All Size (Adjustable)']
        ],
        'topi-law-bucket' => [
            'slug' => 'topi-law-bucket',
            'name' => 'Topi Bucket Hat Trafalgar Law One Piece',
            'price' => 'Rp 125.000',
            'price_raw' => 125000,
            'image' => 'https://i.pinimg.com/736x/bf/37/23/bf37232d67b97378e800131b937fbc98.jpg',
            'category' => 'Topi',
            'badge' => 'nakama Pick',
            'description' => 'Topi bucket bulu/canvas bermotif totol hitam-putih legendaris khas topi kapten Trafalgar Law dari One Piece. Wajib dikoleksi fans OP.',
            'features' => [
                'Bahan Kain Bulu Halus (Sherpa Fleece) nyaman',
                'Motif totol sublimasi anti luntur',
                'Lingkar kepala ukuran standar dewasa 58cm',
                'Brim melingkar pelindung matahari'
            ],
            'sizes' => ['Medium (58cm)']
        ],
        'topi-cyberpunk' => [
            'slug' => 'topi-cyberpunk',
            'name' => 'Topi Baseball Cap Cyberpunk Samurai Neon',
            'price' => 'Rp 115.000',
            'price_raw' => 115000,
            'image' => 'https://i.pinimg.com/1200x/6b/df/a3/6bdfa32561adb697894852536d48766a.jpg',
            'category' => 'Topi',
            'badge' => 'Futuristik',
            'description' => 'Topi baseball bernuansa cyber-techno dengan logo "Samurai" bordir benang merah neon yang menyala terang.',
            'features' => [
                'Bahan Canvas Cargo premium',
                'Bordir benang neon menyala (high visibility)',
                'Gesper belakang tipe velcro strap praktis',
                'Kombinasi panel warna abu-hitam modern'
            ],
            'sizes' => ['All Size (Adjustable)']
        ],
        'topi-beanie' => [
            'slug' => 'topi-beanie',
            'name' => 'Topi Beanie Hat Retro Cozy Black',
            'price' => 'Rp 85.000',
            'price_raw' => 85000,
            'image' => 'https://i.pinimg.com/1200x/cf/a9/c8/cfa9c851641e11bbeb30322f70a34188.jpg',
            'category' => 'Topi',
            'badge' => 'New',
            'description' => 'Topi kupluk/beanie rajut tebal nan elastis. Sangat hangat digunakan saat bepergian ke tempat dingin atau pelengkap fashion streetwear.',
            'features' => [
                'Bahan Rajutan Benang Katun Wol Premium',
                'Sifat kain sangat melar dan kembali ke bentuk semula',
                'Bahan lembut tidak menimbulkan gatal di dahi',
                'Simpel polos tanpa motif cocok dipadukan pakaian apa saja'
            ],
            'sizes' => ['All Size (Super Stretch)']
        ]
    ];

    public function index()
    {
        $products = $this->products;
        
        // Dapatkan produk populer (3 item pertama dari setiap kategori sebagai representasi)
        $popularProducts = [
            $this->products['hoodie-aot'],
            $this->products['kaos-solo-leveling'],
            $this->products['topi-streetwear']
        ];

        return view('chat', compact('products', 'popularProducts'));
    }

    public function show($slug)
    {
        if (!array_key_exists($slug, $this->products)) {
            abort(404);
        }

        $product = $this->products[$slug];
        
        // Ambil produk lain dari kategori yang sama sebagai rekomendasi
        $category = $product['category'];
        $otherProducts = array_filter($this->products, function($p) use ($slug, $category) {
            return $p['slug'] !== $slug && $p['category'] === $category;
        });

        // Jika produk satu kategori tidak cukup, tambahkan produk kategori lain
        if (count($otherProducts) < 2) {
            $filler = array_filter($this->products, function($p) use ($slug) {
                return $p['slug'] !== $slug;
            });
            $otherProducts = array_merge($otherProducts, array_slice($filler, 0, 2 - count($otherProducts)));
        } else {
            // Ambil 2 saja
            $otherProducts = array_slice($otherProducts, 0, 2);
        }

        return view('product', compact('product', 'otherProducts'));
    }

    public function checkout($slug)
    {
        if (!array_key_exists($slug, $this->products)) {
            abort(404);
        }

        $product = $this->products[$slug];
        return view('checkout', compact('product'));
    }

    public function checkoutSuccess()
    {
        return view('checkout_success');
    }
}
