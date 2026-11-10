<?php

namespace App\Http\Controllers;

use App\Models\BatchStok;
use App\Models\MutasiStok;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchStokController extends Controller
{
    // Tambah Batch Stok Baru (Stok Masuk)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'           => 'required|exists:barang,id',
            'nomor_batch'         => 'required|string|max:100',
            'jumlah_awal'         => 'required|integer|min:1',
            'tanggal_kedaluwarsa' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $validated['jumlah_sisa'] = $request->jumlah_awal;
            $validated['created_by']  = auth()->id();
            $validated['status']      = 'Aman';

            $batch = BatchStok::create($validated);

            // Catat Mutasi Masuk
            MutasiStok::create([
                'batch_id' => $batch->id,
                'jenis'    => 'masuk',
                'jumlah'   => $batch->jumlah_awal,
                'catatan'  => 'Penerimaan batch stok awal',
                'user_id'  => auth()->id(),
                'waktu'    => now(),
            ]);

            // Audit Log
            RiwayatAktivitas::create([
                'user_id'    => auth()->id(),
                'aksi'       => 'tambah',
                'entitas'    => 'batch_stok',
                'entitas_id' => $batch->id,
                'keterangan' => "Tambah batch {$batch->nomor_batch} (Jumlah: {$batch->jumlah_awal})",
                'waktu'      => now(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Batch stok berhasil ditambahkan',
                'data'    => $batch
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // List Perlu Ditangani (Batch Kedaluwarsa)
    public function perluDitangani()
    {
        $batch = BatchStok::with('barang')
            ->where('status', 'Kedaluwarsa')
            ->orderBy('tanggal_kedaluwarsa', 'asc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $batch], 200);
    }

    // Eksekusi Penanganan Barang Kedaluwarsa
    public function tanganiKedaluwarsa(Request $request, $id)
    {
        $request->validate([
            'status_penanganan'  => 'required|in:Dimusnahkan,Dikembalikan ke Pemasok,Lainnya',
            'catatan_penanganan' => 'nullable|string',
        ]);

        $batch = BatchStok::find($id);

        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            $jumlahSisaSebelumnya = $batch->jumlah_sisa;

            // Update Status Batch
            $batch->update([
                'status'             => 'Ditangani',
                'status_penanganan'  => $request->status_penanganan,
                'catatan_penanganan' => $request->catatan_penanganan,
                'jumlah_sisa'        => 0, // Sisa diset 0 setelah ditangani
            ]);

            // Catat Mutasi Penanganan
            MutasiStok::create([
                'batch_id' => $batch->id,
                'jenis'    => 'penanganan',
                'jumlah'   => $jumlahSisaSebelumnya,
                'catatan'  => "Penanganan: {$request->status_penanganan}",
                'user_id'  => auth()->id(),
                'waktu'    => now(),
            ]);

            // Audit Log
            RiwayatAktivitas::create([
                'user_id'    => auth()->id(),
                'aksi'       => 'penanganan_kedaluwarsa',
                'entitas'    => 'batch_stok',
                'entitas_id' => $batch->id,
                'keterangan' => "Penanganan batch {$batch->nomor_batch}: {$request->status_penanganan}",
                'waktu'      => now(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Penanganan kedaluwarsa berhasil dicatat',
                'data'    => $batch
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
