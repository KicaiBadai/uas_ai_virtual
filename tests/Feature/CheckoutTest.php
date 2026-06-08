<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function stock_is_reduced_after_successful_checkout()
    {
        // Create a product with known stock
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price_raw' => 1000,
            'price' => 'Rp 1.000',
            'stock' => 10,
        ]);

        // Perform checkout with all required parameters
        $response = $this->postJson(route('checkout.process', ['slug' => $product->slug]), [
            'qty' => 3,
            'size' => 'L',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 10',
            'courier' => 'JNE',
            'payment_method' => 'COD',
            'notes' => 'Tinggalkan di satpam'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.'
        ]);

        // Refresh product and assert stock reduced
        $product->refresh();
        $this->assertEquals(7, $product->stock);

        // Assert order exists in database
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_whatsapp' => '08123456789',
            'quantity' => 3,
            'size' => 'L',
            'status' => 'pending'
        ]);
    }
}
