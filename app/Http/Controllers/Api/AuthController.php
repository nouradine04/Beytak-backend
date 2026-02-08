<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'role']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,agence,user',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        /** @var string $token */
        $token = auth('api')->login($user);

        /** @var User|null $user */
        $user = auth('api')->user();

        return response()->json([
            'status' => 'ok',
            'user' => $user,
            'token' => $token,
            'role' => $user?->role,
            'token_type' => 'bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        /** @var User|null $user */
        $user = auth('api')->user();

        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'role' => $user?->role,
            'user' => $user,
            'token_type' => 'bearer',
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['status' => 'ok', 'message' => 'logged out']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
