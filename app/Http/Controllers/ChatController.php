<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MemoryService;
use Illuminate\Http\JsonResponse;
use App\Models\Product;

class ChatController extends Controller
{
    protected MemoryService $memory;

    public function __construct(MemoryService $memory)
    {
        $this->memory = $memory;
    }

    /**
     * POST /api/chat/send
     * Store user message and return a stub assistant reply (placeholder until LLM integration).
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->session()->getId();
        $userMessage = $validated['message'];

        try {
            // Get context from both short and long-term memory
            $context = $this->memory->getContext($sessionId, $userMessage);
            
            // Get LLM response
            $llmService = app(\App\Services\LlmGatewayService::class);
            $assistantResponse = $llmService->chat($context);
            
            // Remember the conversation
            $this->memory->rememberConversation($sessionId, $userMessage, $assistantResponse);
            
            return response()->json([
                'messages' => [
                    ['role' => 'assistant', 'content' => $assistantResponse]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Произошла ошибка при обработке запроса',
                'messages' => [
                    ['role' => 'assistant', 'content' => 'Извините, произошла ошибка. Пожалуйста, попробуйте снова.']
                ]
            ], 500);
        }
    }

    /**
     * GET /api/chat/history
     * Return full chat history for current session.
     */
    public function history(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();

        try {
            $history = $this->memory->getRecentMessages($sessionId);

            $safeHistory = $history
                ->filter(fn ($msg) => is_array($msg))
                ->map(fn ($msg) => [
                    'role' => $msg['role'] ?? 'assistant',
                    'content' => $msg['content'] ?? '',
                    'ts' => $msg['timestamp'] ?? ($msg['ts'] ?? null),
                ])
                ->values()
                ->toArray();

            return response()->json([
                'history' => $safeHistory,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Chat history error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'history' => [],
            ]);
        }
    }

    /**
     * POST /api/chat/best-prices
     * Get best prices for a product brand (for promotional buttons)
     */
    public function getBestPrices(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
        ]);

        $brand = strtoupper($validated['brand']);
        
        // Get all products for this brand with their forms and dosages
        $products = Product::where('brand', 'like', "%{$brand}%")
            ->where('is_available', true)
            ->select([
                'id',
                'title',
                'brand',
                'price',
                'regular_price',
                'discount_pct',
                'promo_unit_price',
                'dosage_form',
                'dosage_strength',
                'units_per_pack',
                'unit_label',
                'url',
                'source_site',
                'promotion_type',
                'promotion_details'
            ])
            ->orderBy('price')
            ->get();

        // Calculate price per unit for each product
        $productsWithPricePerUnit = $products->map(function ($product) {
            $units = $product->units_per_pack ?? 1;
            
            // Use promotional price if available, otherwise regular price
            $actualPrice = $product->promo_unit_price ?? $product->price;
            
            $product->price_per_unit = $units > 0 ? $actualPrice / $units : $actualPrice;
            $product->display_price = $actualPrice;
            
            // Format dosage info
            $product->formatted_dosage = trim(($product->dosage_strength ?? '') . ' ' . ($product->dosage_form ?? ''));
            
            return $product;
        })->sortBy('price_per_unit');

        // Group by dosage form for better organization
        $groupedProducts = $productsWithPricePerUnit->groupBy('dosage_form');

        // Format response
        $response = [
            'brand' => $brand,
            'total_products' => $products->count(),
            'best_price_overall' => $productsWithPricePerUnit->first() ? [
                'title' => $productsWithPricePerUnit->first()->title,
                'price_per_unit' => number_format($productsWithPricePerUnit->first()->price_per_unit, 2),
                'formatted_dosage' => $productsWithPricePerUnit->first()->formatted_dosage,
                'total_price' => number_format($productsWithPricePerUnit->first()->display_price, 2),
                'units' => $productsWithPricePerUnit->first()->units_per_pack ?? 1,
                'unit_label' => $productsWithPricePerUnit->first()->unit_label ?? 'unidad'
            ] : null,
            'products_by_form' => $groupedProducts->map(function ($products, $form) {
                return [
                    'form' => $form ?: 'Otro',
                    'products' => $products->take(5)->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'title' => $product->title,
                            'brand' => $product->brand,
                            'price_per_unit' => number_format($product->price_per_unit, 2),
                            'total_price' => number_format($product->display_price, 2),
                            'units' => $product->units_per_pack ?? 1,
                            'unit_label' => $product->unit_label ?? 'unidad',
                            'dosage' => $product->formatted_dosage,
                            'has_promotion' => !is_null($product->promo_unit_price),
                            'promotion_type' => $product->promotion_type,
                            'url' => $product->url,
                            'source_site' => $product->source_site
                        ];
                    })->toArray()
                ];
            })->toArray()
        ];

        return response()->json($response);
    }
}
