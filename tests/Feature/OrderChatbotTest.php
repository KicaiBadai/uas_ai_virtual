<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class OrderChatbotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup mock settings in DB for testing
        Setting::set('mistral_api_key', 'mock-api-key');
        Setting::set('mistral_model', 'open-mistral-7b');
    }

    /** @test */
    public function chatbot_user_can_retrieve_order_details_with_matching_resi_and_phone()
    {
        // 1. Create a product and an order
        $product = Product::factory()->create([
            'name' => 'Survey Corps Hoodie',
            'slug' => 'survey-corps-hoodie'
        ]);

        $order = Order::create([
            'receipt_number' => 'AM98765',
            'customer_name' => 'Afnan Aditya',
            'customer_whatsapp' => '08123456789',
            'customer_address' => 'Jakarta',
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'L',
            'shipping_cost' => 15000,
            'total_price' => 200000,
            'status' => 'diproses',
            'payment_method' => 'Transfer Bank',
            'courier' => 'JNE',
            'notes' => 'None'
        ]);

        // 2. Mock Mistral completions:
        // Response 1: Requests tool call 'get_order_details'
        // Response 2: Receives the tool output and returns the final answer
        Http::fake([
            'https://api.mistral.ai/v1/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => '',
                                'tool_calls' => [
                                    [
                                        'id' => 'call_123',
                                        'type' => 'function',
                                        'function' => [
                                            'name' => 'get_order_details',
                                            'arguments' => json_encode([
                                                'receipt_number' => 'AM98765',
                                                'whatsapp_number' => '08123456789'
                                            ])
                                        ]
                                    ]
                                ]
                            ],
                            'finish_reason' => 'tool_calls'
                        ]
                    ]
                ])
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Pesanan dengan resi AM98765 atas nama Afnan Aditya sedang diproses.'
                            ],
                            'finish_reason' => 'stop'
                        ]
                    ]
                ])
        ]);

        // 3. Make POST request to public chat endpoint
        $response = $this->postJson('/chat-ai', [
            'message' => 'Cek status resi AM98765 dengan WhatsApp 08123456789',
            'history' => json_encode([])
        ]);

        // 4. Assert response
        $response->assertStatus(200);
        $response->assertJsonStructure(['reply', 'history']);
        
        $data = $response->json();
        $this->assertStringContainsString('Afnan Aditya', $data['reply']);
        $this->assertStringContainsString('diproses', $data['reply']);
    }

    /** @test */
    public function chatbot_user_fails_order_lookup_if_whatsapp_does_not_match()
    {
        // 1. Create a product and an order
        $product = Product::factory()->create([
            'name' => 'Survey Corps Hoodie',
            'slug' => 'survey-corps-hoodie'
        ]);

        $order = Order::create([
            'receipt_number' => 'AM98765',
            'customer_name' => 'Afnan Aditya',
            'customer_whatsapp' => '08123456789',
            'customer_address' => 'Jakarta',
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'L',
            'shipping_cost' => 15000,
            'total_price' => 200000,
            'status' => 'diproses',
            'payment_method' => 'Transfer Bank',
            'courier' => 'JNE',
            'notes' => 'None'
        ]);

        // 2. Mock Mistral completions:
        // Response 1: Requests tool call 'get_order_details' with wrong WhatsApp
        // Response 2: Receives the tool error and returns the final answer saying it failed
        Http::fake([
            'https://api.mistral.ai/v1/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => '',
                                'tool_calls' => [
                                    [
                                        'id' => 'call_123',
                                        'type' => 'function',
                                        'function' => [
                                            'name' => 'get_order_details',
                                            'arguments' => json_encode([
                                                'receipt_number' => 'AM98765',
                                                'whatsapp_number' => '08999999999' // Wrong whatsapp
                                            ])
                                        ]
                                    ]
                                ]
                            ],
                            'finish_reason' => 'tool_calls'
                        ]
                    ]
                ])
                ->push([
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Maaf, nomor WhatsApp tidak cocok dengan nomor WhatsApp pemesan.'
                            ],
                            'finish_reason' => 'stop'
                        ]
                    ]
                ])
        ]);

        // 3. Make POST request to public chat endpoint
        $response = $this->postJson('/chat-ai', [
            'message' => 'Cek status resi AM98765 dengan WhatsApp 08999999999',
            'history' => json_encode([])
        ]);

        // 4. Assert response
        $response->assertStatus(200);
        $this->assertStringContainsString('tidak cocok', $response->json('reply'));
    }
}
