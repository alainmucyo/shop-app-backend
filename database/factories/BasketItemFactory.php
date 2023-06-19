<?php

namespace Database\Factories;

use App\Models\BasketItem;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BasketItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'basket_id' => Basket::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
