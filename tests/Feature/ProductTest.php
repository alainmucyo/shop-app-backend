<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    private $token;

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
        $adminRole = Role::create(["name" => "admin"]);
        $user->roles()->attach($adminRole);
    }

    public function testAdminCanCreateProduct()
    {
        // Arrange
        $productData = ['name' => 'Test Product', 'price' => 123];

        // Act
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->postJson('/api/products', $productData);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', $productData);
    }

    public function testAdminCanUpdateProduct()
    {
        // Arrange
        $product = Product::factory()->create();
        $updatedProductData = ['name' => 'Updated Product', 'price' => 456];

        // Act
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->putJson("/api/products/{$product->id}", $updatedProductData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $updatedProductData);
    }

    public function testAdminCanDeleteProduct()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->deleteJson("/api/products/{$product->id}");

        // Assert
        $response->assertStatus(204);
        $this->assertSoftDeleted($product);
    }

    public function testAdminCanRetrieveAllProducts()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->withHeaders(['Authorization' => "Bearer {$this->token}"])
            ->getJson("/api/products");

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $product->toArray());
    }
}
