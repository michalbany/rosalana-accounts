<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // 1. Validace
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->ok('User registered', [
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $credentials = $request->only('email', 'password');

        // Attempt vrátí token, nebo false
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->unauthorized(new \Exception('Invalid credentials'));
        }

        // Tady máme token
        return $this->ok('Logged in', [
            'user' => auth()->user(),
            'token' => $token,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->ok('User info', [
            'user' => auth()->user(),
        ]);
    }

    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return $this->ok('Token refreshed', [
                'token' => $newToken,
            ]);

        } catch (\Exception $e) {
            return $this->unauthorized(new \Exception('Token expired'));
        }
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->ok('Logged out');
    }
}
