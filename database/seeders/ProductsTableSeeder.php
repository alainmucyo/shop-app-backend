<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['name' => 'Pioneer DJ Mixer', 'price' => 699],
            ['name' => 'Roland Wave Sampler', 'price' => 485],
            ['name' => 'Reloop Headphone', 'price' => 159],
            ['name' => 'Rokit Monitor', 'price' => 189.9],
            ['name' => 'Fisherprice Baby Mixer', 'price' => 120]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
