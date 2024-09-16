<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['login', 'signUp']),
        ];
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout() {}

    public function signUp(SignUpRequest $request) {}

    public function refreshUserToken() {}
}
