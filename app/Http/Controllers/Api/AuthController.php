<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }


    // Login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string'],
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        return $this->respondWithToken($token, $user);
    }


    // Logout
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }


    // Self
    public function self(): JsonResponse
    {
        return response()->json(Auth::guard('api')->user());
    }


    // Refresh token
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::guard('api')->refresh(), Auth::guard('api')->user());
    }


    // Respond with token
    protected function respondWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}
