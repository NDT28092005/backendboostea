<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Missing token'], 400);
        }

        // ✅ Verify token đúng với Google
        $googleUser = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$token}");

        if ($googleUser->failed()) {
            return response()->json(['error' => 'Invalid Google token'], 401);
        }

        $data = $googleUser->json();

        $email = $data['email'] ?? null;
        $googleId = $data['sub'] ?? null;
        $name = $data['name'] ?? "Google User";

        if (!$email || !$googleId) {
            return response()->json(['error' => 'Email or Google ID missing'], 400);
        }

        // ✅ Nếu user tồn tại
        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleId,
                    'email_verified_at' => now(),
                ]);
            }
        } else {
            // ✅ Tạo user mới
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]);
        }

        // ✅ Token cho React lưu localStorage
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user'   => $user,
            'token'  => $accessToken,
        ]);
    }
}