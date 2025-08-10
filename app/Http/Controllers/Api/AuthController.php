<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('student');

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token
        ], 'User registered successfully');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return ApiResponse::success([
                'user' => $user,
                'token' => $token
            ], 'Login successful');
        }

        return ApiResponse::error('Unauthorized', 401);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();

        // Include roles in the response
        $user->roles = $roles;
        return ApiResponse::success($user, 'User retrieved successfully');
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token
        ], 'Token refreshed successfully');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, 'Logged out successfully');
    }
}
