<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->json('POST', '/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'created_at', 'updated_at'], 'token']);
    }

    public function testUserCanLogin()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('POST', '/api/logout');

        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Successfully logged out']);
    }

    // test me route
    public function testUserCanGetAuthenticatedUserDetails()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('GET', '/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);
    }
}
