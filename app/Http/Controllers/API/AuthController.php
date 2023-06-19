<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error during registration: ' . $e->getMessage());
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $validatedData['name'] = $validatedData['firstName'] . ' ' . $validatedData['lastName'];
        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);
        $role = Role::where('name', 'user')->first();
        $user->roles()->attach($role);
        // Create a new basket for the user
        Basket::create(['user_id' => $user->id]);

        $token = $user->createToken('api-token')->plainTextToken;

        Log::info('User registered successfully: ' . $user->name);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation error during login: ' . $e->getMessage());
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        if (!auth()->attempt($credentials)) {
            Log::warning('Invalid login attempt: ' . $request->email);
            return response()->json(['error' => 'Invalid login credentials'], 400);
        }

        $token = auth()->user()->createToken('api-token')->plainTextToken;

        Log::info('User logged in successfully: ' . auth()->user()->name);

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        // Get user who initiated the request
        $user = $request->user();
        // Revoke all tokens...
        $user->tokens()->delete();

        Log::info('User logged out: ' . $user->name);

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function me(): \Illuminate\Http\JsonResponse
    {
        Log::info('User details fetched: ' . auth()->user()->name);
        return response()->json(auth()->user());
    }
}
