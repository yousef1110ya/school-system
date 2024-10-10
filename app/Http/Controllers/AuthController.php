<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        $user = User::create($fields);
        $token = $user->createToken($request->name);
        return response()->json([
            'message' => 'welcome to the app',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'invalid email or password'
            ], 401);
        }
        $token = $user->createToken($user->name);
        return response()->json([
            'message' => 'welcome to the app',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout successfully'
        ], 200);
    }
}