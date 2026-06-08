<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class MistralService
{
    /**
     * Send chat request to Mistral AI with automatic tool execution loop.
     *
     * @param array $messages Message history.
     * @param string $systemPrompt System instructions.
     * @param array $tools Function/tool definitions.
     * @param callable|null $toolExecutor Callback to execute a tool: fn($name, $args) => string response.
     * @return array Contains 'reply' and 'history'.
     */
    public function sendChat(array $messages, string $systemPrompt, array $tools = [], callable $toolExecutor = null): array
    {
        $apiKey = Setting::get('mistral_api_key') ?: env('MISTRAL_API_KEY');
        $model = Setting::get('mistral_model', 'open-mistral-7b');

        if (empty($apiKey)) {
            return [
                'reply' => '⚠️ Mistral API Key belum diatur di dashboard admin. Silakan atur terlebih dahulu untuk menggunakan asisten AI.',
                'history' => $messages
            ];
        }

        // Setup messages with system prompt at index 0
        $formattedMessages = $messages;
        $systemMsg = ['role' => 'system', 'content' => $systemPrompt];
        
        if (empty($formattedMessages)) {
            $formattedMessages[] = $systemMsg;
        } else {
            if ($formattedMessages[0]['role'] === 'system') {
                $formattedMessages[0] = $systemMsg;
            } else {
                array_unshift($formattedMessages, $systemMsg);
            }
        }

        // Prepare request body
        $body = [
            'model' => $model,
            'temperature' => 0.0,
            'max_tokens' => 800,
            'messages' => $formattedMessages
        ];

        if (!empty($tools)) {
            $body['tools'] = $tools;
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.mistral.ai/v1/chat/completions', $body);

            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? $response->body() ?? 'Unknown API Error';
                Log::error('Mistral API request failed: ' . $errorMessage);
                return [
                    'reply' => '⚠️ Gagal terhubung ke Mistral AI: ' . $errorMessage,
                    'history' => $messages
                ];
            }

            $data = $response->json();
            $choice = $data['choices'][0] ?? null;
            if (!$choice) {
                return [
                    'reply' => 'Maaf, sepertinya asisten sedang kelelahan. Bisa tanyakan kembali? 🙏',
                    'history' => $messages
                ];
            }

            $choiceMessage = $choice['message'] ?? [];
            
            // Check if model wants to call a tool/function
            if (!empty($choiceMessage['tool_calls']) && $toolExecutor !== null) {
                // Add assistant response containing the tool calls to the history
                $formattedMessages[] = $choiceMessage;

                foreach ($choiceMessage['tool_calls'] as $toolCall) {
                    $funcName = $toolCall['function']['name'] ?? '';
                    $funcArgsRaw = $toolCall['function']['arguments'] ?? '{}';
                    $funcArgs = json_decode($funcArgsRaw, true) ?? [];
                    $toolCallId = $toolCall['id'] ?? '';

                    Log::info("Mistral requested tool execution: {$funcName} with args: " . json_encode($funcArgs));

                    // Execute tool
                    try {
                        $toolResult = $toolExecutor($funcName, $funcArgs);
                    } catch (\Throwable $e) {
                        Log::error("Error executing tool {$funcName}: " . $e->getMessage());
                        $toolResult = json_encode(['success' => false, 'error' => $e->getMessage()]);
                    }

                    // Append tool response to messages
                    $formattedMessages[] = [
                        'role' => 'tool',
                        'name' => $funcName,
                        'content' => $toolResult,
                        'tool_call_id' => $toolCallId
                    ];
                }

                // Remove the system prompt before recursion because sendChat re-adds it
                if ($formattedMessages[0]['role'] === 'system') {
                    array_shift($formattedMessages);
                }

                // Recurse to let the LLM generate the final text based on tool output
                return $this->sendChat($formattedMessages, $systemPrompt, $tools, $toolExecutor);
            }

            $reply = $choiceMessage['content'] ?? 'Maaf, sepertinya asisten sedang kelelahan. Bisa tanyakan kembali? 🙏';
            
            // Append assistant response to messages (without system prompt in history return)
            if ($formattedMessages[0]['role'] === 'system') {
                array_shift($formattedMessages);
            }
            $formattedMessages[] = ['role' => 'assistant', 'content' => $reply];

            return [
                'reply' => $reply,
                'history' => $formattedMessages
            ];

        } catch (\Throwable $e) {
            Log::error('Exception in MistralService: ' . $e->getMessage());
            return [
                'reply' => '⚠️ Terjadi kesalahan koneksi internal: ' . $e->getMessage(),
                'history' => $messages
            ];
        }
    }
}
