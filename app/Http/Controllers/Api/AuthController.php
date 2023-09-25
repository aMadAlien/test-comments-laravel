<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function respondWithToken()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'type' => 'Bearer',
            'expires_in' => \Config::get('jwt.ttl')
        ]);
    }
}