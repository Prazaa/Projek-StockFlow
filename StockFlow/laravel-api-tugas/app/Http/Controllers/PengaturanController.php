<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    // Ambil Seluruh Pengaturan (FR-25)
    public function index()
    {
        $pengaturan = Pengaturan::all()->pluck('nilai', 'kunci');

        return response()->json(['status' => 'success', 'data' => $pengaturan], 200);
    }

    // Ubah Nilai Pengaturan (Khusus Pemilik)
    public function update(Request $request)
    {
        $request->validate([
            'ambang_peringatan_hari' => 'required|numeric|min:1|max:365',
        ]);

        $pengaturan = Pengaturan::updateOrCreate(
            ['kunci' => 'ambang_peringatan_hari'],
            ['nilai' => $request->ambang_peringatan_hari]
        );

        // Audit Log
        RiwayatAktivitas::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'ubah_pengaturan',
            'entitas'    => 'pengaturan',
            'entitas_id' => null,
            'keterangan' => "Mengubah ambang peringatan kedaluwarsa menjadi {$request->ambang_peringatan_hari} hari",
            'waktu'      => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengaturan berhasil diperbarui',
            'data'    => $pengaturan
        ], 200);
    }
}
