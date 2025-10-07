<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductManagementController extends Controller
{
    /**
     * Create a new product for a seller
     * POST /api/external/products
     */
    public function createProduct(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required|exists:sellers,id',
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'image' => 'required|url',
            'sku' => 'nullable|string|unique:products,sku',
            'barcode' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'condition' => 'nullable|in:new,used,refurbished',
            'is_digital' => 'nullable|boolean',
            'download_link' => 'nullable|url',
            'tags' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|array',
            'status' => 'nullable|in:draft,active,inactive,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Verify seller owns the shop
            $seller = Seller::findOrFail($request->seller_id);
            $shop = Shop::where('id', $request->shop_id)
                       ->where('seller_id', $request->seller_id)
                       ->firstOrFail();

            // Create the product
            $productData = $request->all();
            $productData['status'] = $request->get('status', 'draft');
            $productData['condition'] = $request->get('condition', 'new');
            $productData['is_digital'] = $request->get('is_digital', false);

            $product = Product::create($productData);

            // Update shop product count
            $shop->increment('total_products');

            DB::commit();

            Log::info('Product created via external API', [
                'product_id' => $product->id,
                'seller_id' => $seller->id,
                'shop_id' => $shop->id,
                'created_by' => 'external_api'
            ]);

            // Trigger webhook
            \App\Http\Controllers\Api\WebhookController::sendWebhook('product.created', [
                'product_id' => $product->id,
                'seller_id' => $seller->id,
                'shop_id' => $shop->id,
                'name' => $product->name,
                'price' => $product->price,
                'status' => $product->status
            ], $seller->id);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['seller', 'shop'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create product via external API', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing product
     * PUT /api/external/products/{id}
     */
    public function updateProduct(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string|max:100',
            'image' => 'sometimes|url',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'barcode' => 'sometimes|string',
            'weight' => 'sometimes|numeric|min:0',
            'dimensions' => 'sometimes|array',
            'brand' => 'sometimes|string|max:100',
            'model' => 'sometimes|string|max:100',
            'condition' => 'sometimes|in:new,used,refurbished',
            'is_digital' => 'sometimes|boolean',
            'download_link' => 'sometimes|url',
            'tags' => 'sometimes|array',
            'meta_title' => 'sometimes|string|max:255',
            'meta_description' => 'sometimes|string|max:500',
            'seo_keywords' => 'sometimes|array',
            'status' => 'sometimes|in:draft,active,inactive,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product->update($request->all());

            DB::commit();

            Log::info('Product updated via external API', [
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'updated_by' => 'external_api'
            ]);

            // Trigger webhook
            \App\Http\Controllers\Api\WebhookController::sendWebhook('product.updated', [
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'shop_id' => $product->shop_id,
                'name' => $product->name,
                'price' => $product->price,
                'status' => $product->status
            ], $product->seller_id);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load(['seller', 'shop'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update product via external API', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk create products
     * POST /api/external/products/bulk
     */
    public function bulkCreateProducts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1|max:100',
            'products.*.seller_id' => 'required|exists:sellers,id',
            'products.*.shop_id' => 'required|exists:shops,id',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'required|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock' => 'required|integer|min:0',
            'products.*.category' => 'required|string|max:100',
            'products.*.image' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $createdProducts = [];
            $failedProducts = [];

            foreach ($request->products as $index => $productData) {
                try {
                    // Verify seller owns the shop
                    $seller = Seller::findOrFail($productData['seller_id']);
                    $shop = Shop::where('id', $productData['shop_id'])
                               ->where('seller_id', $productData['seller_id'])
                               ->firstOrFail();

                    $productData['status'] = $productData['status'] ?? 'draft';
                    $productData['condition'] = $productData['condition'] ?? 'new';
                    $productData['is_digital'] = $productData['is_digital'] ?? false;

                    $product = Product::create($productData);
                    $shop->increment('total_products');

                    $createdProducts[] = $product;

                } catch (\Exception $e) {
                    $failedProducts[] = [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'data' => $productData
                    ];
                }
            }

            DB::commit();

            Log::info('Bulk products created via external API', [
                'created_count' => count($createdProducts),
                'failed_count' => count($failedProducts),
                'created_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bulk product creation completed',
                'data' => [
                    'created_products' => $createdProducts,
                    'failed_products' => $failedProducts,
                    'summary' => [
                        'total_requested' => count($request->products),
                        'successfully_created' => count($createdProducts),
                        'failed' => count($failedProducts)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to bulk create products via external API', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk create products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product by ID
     * GET /api/external/products/{id}
     */
    public function getProduct($id): JsonResponse
    {
        try {
            $product = Product::with(['seller', 'shop', 'reviews'])
                            ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get products by seller
     * GET /api/external/sellers/{sellerId}/products
     */
    public function getSellerProducts(Request $request, $sellerId): JsonResponse
    {
        try {
            $seller = Seller::findOrFail($sellerId);

            $products = Product::where('seller_id', $sellerId)
                ->with(['shop', 'reviews'])
                ->when($request->get('status'), function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($request->get('category'), function ($query, $category) {
                    return $query->where('category', $category);
                })
                ->when($request->get('shop_id'), function ($query, $shopId) {
                    return $query->where('shop_id', $shopId);
                })
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Seller not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete a product
     * DELETE /api/external/products/{id}
     */
    public function deleteProduct($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            
            DB::beginTransaction();

            // Update shop product count
            if ($product->shop) {
                $product->shop->decrement('total_products');
            }

            $product->delete();

            DB::commit();

            Log::info('Product deleted via external API', [
                'product_id' => $id,
                'seller_id' => $product->seller_id,
                'deleted_by' => 'external_api'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete product via external API', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product stock
     * PATCH /api/external/products/{id}/stock
     */
    public function updateStock(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'operation' => 'sometimes|in:set,increment,decrement'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            $operation = $request->get('operation', 'set');
            $stock = $request->get('stock');

            switch ($operation) {
                case 'increment':
                    $product->increment('stock', $stock);
                    break;
                case 'decrement':
                    $product->decrement('stock', $stock);
                    break;
                default:
                    $product->update(['stock' => $stock]);
            }

            Log::info('Product stock updated via external API', [
                'product_id' => $id,
                'operation' => $operation,
                'stock' => $stock,
                'new_stock' => $product->fresh()->stock
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => [
                    'product_id' => $product->id,
                    'current_stock' => $product->fresh()->stock
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update product stock via external API', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
