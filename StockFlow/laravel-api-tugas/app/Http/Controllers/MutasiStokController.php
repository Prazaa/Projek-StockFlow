<?php

namespace App\Http\Controllers;

use App\Models\BatchStok;
use App\Models\MutasiStok;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiStokController extends Controller
{
    // Algoritma Pengeluaran Barang FEFO
    public function keluarFEFO(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah'    => 'required|integer|min:1',
            'catatan'   => 'nullable|string',
        ]);

        $barangId = $request->barang_id;
        $jumlahDibutuhkan = $request->jumlah;

        DB::beginTransaction();
        try {
            // Ambil batch berurut dari kedaluwarsa paling dekat (FEFO)
            $batches = BatchStok::where('barang_id', $barangId)
                ->where('jumlah_sisa', '>', 0)
                ->where('status', '!=', 'Ditangani')
                ->orderBy('tanggal_kedaluwarsa', 'asc')
                ->lockForUpdate()
                ->get();

            $totalTersedia = $batches->sum('jumlah_sisa');
            if ($totalTersedia < $jumlahDibutuhkan) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Total stok tidak mencukupi. Tersedia: ' . $totalTersedia
                ], 400);
            }

            $sisaPotong = $jumlahDibutuhkan;

            foreach ($batches as $batch) {
                if ($sisaPotong <= 0) break;

                $dipotong = min($batch->jumlah_sisa, $sisaPotong);
                $batch->jumlah_sisa -= $dipotong;
                $batch->save();

                // Record Mutasi Keluar
                MutasiStok::create([
                    'batch_id' => $batch->id,
                    'jenis'    => 'keluar',
                    'jumlah'   => $dipotong,
                    'catatan'  => $request->catatan ?? 'Pengeluaran FEFO',
                    'user_id'  => auth()->id(),
                    'waktu'    => now(),
                ]);

                $sisaPotong -= $dipotong;
            }

            // Audit Log
            RiwayatAktivitas::create([
                'user_id'    => auth()->id(),
                'aksi'       => 'pengeluaran_stok',
                'entitas'    => 'barang',
                'entitas_id' => $barangId,
                'keterangan' => "Pengeluaran stok FEFO sebanyak {$jumlahDibutuhkan} pcs",
                'waktu'      => now(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengeluaran stok FEFO berhasil diproses'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
