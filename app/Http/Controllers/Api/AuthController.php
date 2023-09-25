<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'register'
            ]
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        auth()->login($user);

        return self::respondWithToken();
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Failed'
            ]);
        }
        return self::respondWithToken(auth()->user()->toArray());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function respondWithToken(array $user = null)
    {
        $response = [
            'access_token' => auth()->refresh(),
            'type' => 'Bearer',
            'expires_in' => \Config::get('jwt.ttl')
        ];

        if (isset($user)) {
            $response['user'] = $user;
        }

        return response()->json($response);
    }
}