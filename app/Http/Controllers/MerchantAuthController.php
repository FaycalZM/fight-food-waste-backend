<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MerchantAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create($fields);
        $token = $user->createToken($request->name);

        return [
            'User' => $user,
            'token' => $token->plainTextToken
        ];
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:Users',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return [
            'User' => $user,
            'token' => $user->createToken($user->name)->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->User()->tokens()->delete();
        return [
            'message' => 'User Logged out successfully'
        ];
    }
}
