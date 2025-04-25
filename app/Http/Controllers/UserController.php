<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user(); // Ambil data log in user

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return response()->json([
            'message' => 'User berhasil di update',
            'user' => $user,
        ]);
    }

    public function deleteWithConfirmation(Request $request)
    {
        $user = $request->user();

        // Step 1: Minta request untuk delete dengan password
        if ($request->has('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password Salah!'
                ], 403);
            }

            $token = Str::random(40);
            Cache::put("delete_user_{$user->id}", $token, now()->addMinutes(5));

            return response()->json([
                'message' => 'Apakah kamu yakin untuk delete akun? Kirim ulang token dalam 5 menit untuk mengkonfirmasi.',
                'deletion_token' => $token
            ]);
        }

        // Step 2: Konfirmasi delete dengan token
        if ($request->has('deletion_token')) {
            $cachedToken = Cache::get("delete_user_{$user->id}");

            if ($cachedToken !== $request->deletion_token) {
                return response()->json([
                    'message' => 'Token salah atau sudah expired!'
                ], 403);
            }

            // Delete token (logout)
            $request->user()->currentAccessToken()->delete();

            // Delete user
            $user->delete();
            Cache::forget("delete_user_{$user->id}");

            return response()->json([
                'message' => 'Akun berhasil di delete dan sudah di logout!'
            ]);
        }

        // Jika tidak ada data yang diberikan
        return response()->json([
            'message' => 'Password atau token dibutuhkan!'
        ], 400);
    }
}
