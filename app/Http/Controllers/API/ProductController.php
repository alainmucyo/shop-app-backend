<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $products = Product::all();
        Log::info('Retrieved all products');

        return response()->json($products);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('create', Product::class);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error while creating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $product = Product::create($request->all());

        Log::info('Product created by user ID: ' . $request->user()->id . ', Product: ' . $product->name);

        return response()->json($product, 201);
    }

    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        Log::info('Retrieved product: ' . $product->name);
        return response()->json($product);
    }

    public function update(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $product);

        $product->update($request->all());

        Log::info('Product updated by user ID: ' . $request->user()->id . ', Product: ' . $product->name);

        return response()->json($product);
    }

    public function destroy(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $product);

        $productName = $product->name;

        $product->delete();

        Log::info('Product deleted by user ID: ' . $request->user()->id . ', Product: ' . $productName);

        return response()->json(null, 204);
    }
}
