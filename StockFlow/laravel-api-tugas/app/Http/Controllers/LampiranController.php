<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use App\Models\BatchStok;
use Illuminate\Http\Request;

class LampiranController extends Controller
{
    // Upload Foto Lampiran Batch / Penanganan (FR-17, FR-26)
    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batch_stok,id',
            'jenis'    => 'required|in:bukti_batch,bukti_penanganan',
            'foto'     => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2 MB
        ]);

        $batch = BatchStok::find($request->batch_id);

        // Simpan file ke folder storage/app/public/lampiran
        $path = $request->file('foto')->store('lampiran', 'public');

        $lampiran = Lampiran::create([
            'batch_id' => $batch->id,
            'jenis'    => $request->jenis,
            'path'     => $path,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto bukti berhasil diunggah',
            'data'    => [
                'id'       => $lampiran->id,
                'batch_id' => $lampiran->batch_id,
                'jenis'    => $lampiran->jenis,
                'url'      => asset('storage/' . $lampiran->path),
            ]
        ], 201);
    }
}
