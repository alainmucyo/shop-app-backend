<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasketItemResource;
use App\Models\BasketEvent;
use App\Models\BasketItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BasketItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // Retrieve user's basket items
        $basketItems = $request->user()->basket->items;

        Log::info('Retrieved basket items for user: ' . $request->user()->name);

        return BasketItemResource::collection($basketItems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return BasketItemResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id', // Ensure the product exists
                'quantity' => 'required|integer|min:1', // The quantity must be at least 1
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error while adding item to basket: ' . $e->getMessage());
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }


        $basket = $request->user()->basket;

        // Check if the product is already in the basket
        $basketItem = $basket->items()->where('product_id', $request->product_id)->first();

        if ($basketItem) {
            // If the product is already in the basket, increment the quantity
            $basketItem->increment('quantity', $request->quantity);
        } else {
            // If the product is not in the basket, create a new basket item
            $basketItem = BasketItem::create([
                'basket_id' => $basket->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Create 'added' basket event
        BasketEvent::create([
            'basket_id' => $basket->id,
            'product_id' => $request->product_id,
            'event_type' => 'added'
        ]);

        Log::info('Item added to basket for user: ' . $request->user()->name);

        return new BasketItemResource($basketItem);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\BasketItem $basketItem
     * @return BasketItemResource|\Illuminate\Http\JsonResponse
     */
    public function show(BasketItem $basketItem): BasketItemResource
    {
        // Ensure the authenticated user owns this basket item
        if (auth()->user()->id !== $basketItem->basket->user_id) {
            Log::warning('Unauthorized attempt to access basket item by user: ' . auth()->user()->name);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Log::info('Retrieved specific basket item for user: ' . auth()->user()->name);

        return new BasketItemResource($basketItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\BasketItem $basketItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, BasketItem $basketItem): \Illuminate\Http\JsonResponse
    {
        // Ensure the authenticated user owns this basket item
        if ($request->user()->id !== $basketItem->basket->user_id) {
            Log::warning('Unauthorized attempt to delete basket item by user: ' . $request->user()->name);
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $productId = $basketItem->product_id;
        // Remove the basket item
        $basketItem->delete();
        BasketEvent::create([
            'basket_id' => auth()->user()->basket->id,
            'product_id' => $productId,
            'event_type' => 'removed'
        ]);

        Log::info('Item removed from basket for user: ' . $request->user()->name);

        return response()->json(['message' => 'Basket item removed']);
    }
}
