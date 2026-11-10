<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List Seluruh Staf
    public function index()
    {
        $staf = User::where('peran', 'staf')->get();

        return response()->json(['status' => 'success', 'data' => $staf], 200);
    }

    // Tambah Akun Staf Baru (FR-04)
    public function store(Request $request)
    {
        $validated =$request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        $staf = User::create([
            'nama'     => $validated['nama'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'peran'    => 'staf',
            'aktif'    => 1,
        ]);

        // Audit Log
        RiwayatAktivitas::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'tambah',
            'entitas'    => 'users',
            'entitas_id' => $staf->id,
            'keterangan' => "Membuat akun staf baru: {$staf->nama}",
            'waktu'      => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Akun staf berhasil dibuat',
            'data'    => $staf
        ], 201);
    }

    // Aktifkan / Nonaktifkan Akun Staf (FR-05)
    public function toggleAktif($id)
    {
        $staf = User::where('peran', 'staf')->find($id);

        if (!$staf) {
            return response()->json(['status' => 'error', 'message' => 'Staf tidak ditemukan'], 404);
        }

        $staf->aktif = !$staf->aktif;
        $staf->save();

        // Jika status dinonaktifkan, cabut semua token login aktif milik staf tersebut (FR-05)
        if (!$staf->aktif) {$staf->tokens()->delete();
        }

        $statusTeks =$staf->aktif ? 'diaktifkan' : 'dinonaktifkan';

        // Audit Log
        RiwayatAktivitas::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'ubah',
            'entitas'    => 'users',
            'entitas_id' => $staf->id,
            'keterangan' => "Akun staf {$staf->nama} {$statusTeks}",
            'waktu'      => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Akun staf berhasil {$statusTeks}",
            'data'    => $staf
        ], 200);
    }
}
