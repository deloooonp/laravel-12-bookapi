<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:dns|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
        ];

        $validatedData = Validator::make($request->all(), $rules);


        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Registrasi Gagal',
                'error' => $validatedData->errors(),
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi Berhasil!',
        ], 200);
    }

    // Login and get token
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah!'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User berhasil Login!',
            'token' => $token,
        ], 200);
    }

    // Get authenticated user
    public function user(Request $request)
    {
        return $request->user();
    }

    // Logout (delete current token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User berhasil Logout!',
        ], 200);
    }
}
