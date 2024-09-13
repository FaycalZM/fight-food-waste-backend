<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VolunteerAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:volunteers,email',
            'password' => 'required|string|confirmed',
            'skill_id' => 'required|integer'
        ]);

        $volunteer = Volunteer::create($fields);
        $token = $volunteer->createToken($request->name);

        return [
            'volunteer' => $volunteer,
            'token' => $token->plainTextToken
        ];
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:volunteers',
            'password' => 'required|string'
        ]);

        $volunteer = Volunteer::where('email', $request->email)->first();
        if (!$volunteer || !Hash::check($request->password, $volunteer->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return [
            'volunteer' => $volunteer,
            'token' => $volunteer->createToken($volunteer->name)->plainTextToken
        ];
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Volunteer Logged out successfully'
        ];
    }
}
