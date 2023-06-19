<?php

namespace Tests\Feature;

use App\Models\BasketItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasketItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $userData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $userData);
        $this->token = $response->getData()->token;

        // Get the user and attach the admin role
        $user = User::where('email', 'test@test.com')->first();
        $userRole = Role::create(["name" => "user"]);
        $user->roles()->attach($userRole);
        $this->user = $user;
    }

    public function testUserCanCreateBasketItem()
    {
        $product = Product::factory()->create();

        $response = $this->withToken($this->token)
            ->postJson(route('basket-items.store'), ['product_id' => $product->id, 'quantity' => 1]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('basket_items', [
            'basket_id' => $this->user->basket->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('basket_events', [
            'basket_id' => $this->user->basket->id,
            'product_id' => $product->id,
            'event_type' => 'added',
        ]);
    }

    public function testUserCanRetrieveBasketItem()
    {
        $basketItem = BasketItem::factory()->for($this->user->basket)->create();

        $response = $this->withToken($this->token)
            ->getJson(route('basket-items.show', $basketItem));

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $basketItem->id]);
    }

    public function testUserCanDeleteBasketItem()
    {
        $basketItem = BasketItem::factory()->for($this->user->basket)->create();

        $response = $this->withToken($this->token)
            ->deleteJson(route('basket-items.destroy', $basketItem));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('basket_items', ['id' => $basketItem->id]);

        $this->assertDatabaseHas('basket_events', [
            'basket_id' => $this->user->basket->id,
            'product_id' => $basketItem->product_id,
            'event_type' => 'removed',
        ]);
    }
}
