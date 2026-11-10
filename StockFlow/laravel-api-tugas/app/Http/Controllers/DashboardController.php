<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BatchStok;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary()
    {
        // 1. Total Jenis Barang
        $totalBarang = Barang::count();

        // 2. Hitung Barang Stok Minim (Aman dari error SQL stok_total)
        $stokMinim = Barang::all()->filter(function ($barang) {
            $totalStok = 0;

            // Jika ada relasi batchStok/batch_stok, hitung dari total jumlah_sisa
            if (method_exists($barang, 'batchStok') && $barang->batchStok) {
                $totalStok = $barang->batchStok->sum('jumlah_sisa');
            } elseif (isset($barang->stok_total)) {
                $totalStok = $barang->stok_total;
            }

            return $totalStok <= ($barang->stok_minimum ?? 0);
        })->count();

        // 3. Total Batch Mendekati Kedaluwarsa (<= 30 hari lagi)
        $batchMendekatiKedaluwarsa = BatchStok::where('jumlah_sisa', '>', 0)
            ->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(30))
            ->whereDate('tanggal_kedaluwarsa', '>=', now())
            ->count();

        // 4. Total Batch Sudah Kedaluwarsa
        $batchKedaluwarsa = BatchStok::where('jumlah_sisa', '>', 0)
            ->whereDate('tanggal_kedaluwarsa', '<', now())
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_barang' => $totalBarang,
                'stok_minim' => $stokMinim,
                'batch_mendekati_kedaluwarsa' => $batchMendekatiKedaluwarsa,
                'batch_kedaluwarsa' => $batchKedaluwarsa,
            ]
        ], 200);
    }
}