<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/auth/register",
     *   summary="Register a new user",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", maxLength=255),
     *       @OA\Property(property="email", type="string", format="email", maxLength=255),
     *       @OA\Property(property="password", type="string", format="password", minLength=8),
     *       @OA\Property(property="password_confirmation", type="string", format="password", minLength=8)
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="User registered successfully",
     *   ),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'User registered successfully.',
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   summary="Login user",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email"),
     *       @OA\Property(property="password", type="string", format="password")
     *     )
     *   ),
     *   @OA\Response(response=200, description="User logged in successfully"),
     *   @OA\Response(response=422, description="Invalid credentials or validation error")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var \App\Models\User|null $user */
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 422);
        }

        // Optionally revoke previous tokens for this user
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'User logged in successfully.',
        ]);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/logout",
     *   summary="Logout authenticated user",
     *   tags={"Auth"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="User logged out successfully"),
     *   @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'User logged out successfully.',
        ]);
    }

    /**
     * @OA\Get(
     *   path="/api/auth/userAutenticate",
     *   summary="Get authenticated user",
     *   tags={"Auth"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Authenticated user data"),
     *   @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function userAutenticate(Request $request): JsonResponse
    {
        return response()->json([
            'data' => [
                'user' => $request->user(),
            ],
        ]);
    }
}