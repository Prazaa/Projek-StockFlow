<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login User (Pemilik / Staf)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        // Cek keberadaan user dan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username atau password salah'
            ], 401);
        }

        // Cek status keaktifan akun (FR-05)
        if (!$user->aktif) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Anda dinonaktifkan. Silakan hubungi pemilik.'
            ], 403);
        }

        // Buat token Sanctum
        $token = $user->createToken('android_token')->plainTextToken;

        // Catat audit log
        RiwayatAktivitas::create([
            'user_id'    => $user->id,
            'aksi'       => 'login',
            'entitas'    => 'users',
            'entitas_id' => $user->id,
            'keterangan' => "User {$user->nama} ({$user->peran}) berhasil login",
            'waktu'      => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'nama'     => $user->nama,
                'username' => $user->username,
                'peran'    => $user->peran,
            ]
        ], 200);
    }

    // Logout & Revoke Token
    public function logout(Request $request)
    {
        $user = $request->user();

        // Catat audit log sebelum revoke token
        RiwayatAktivitas::create([
            'user_id'    => $user->id,
            'aksi'       => 'logout',
            'entitas'    => 'users',
            'entitas_id' => $user->id,
            'keterangan' => "User {$user->nama} logout",
            'waktu'      => now(),
        ]);

        // Hapus token aktif saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }

    // Profil User Aktif
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user'   => $request->user()
        ], 200);
    }
}
