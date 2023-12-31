<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            throw ValidationException::withMessages([
                'message' => 'Credintials are not correct'
            ]);
        }

        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'Credintials are not correct'
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'messages' => 'User logged out successfully'
        ]);
    }

    public function register(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
}
