<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)
                            ->where('isActive', true)
                            ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials do not match out records.'],
                ]);
            }

            $user->tokens()->delete();

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully!',
                'user' => $user,
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Error logging in: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error logging in!',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully!',
            ]);
        } catch (Exception $e) {
            Log::error('Error logging out: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error logging out!',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
