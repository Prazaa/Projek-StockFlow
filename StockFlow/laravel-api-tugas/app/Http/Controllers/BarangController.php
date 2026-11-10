<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar seluruh barang aktif
    public function index()
    {
        // Mengambil barang aktif beserta relasi batchStok
        $barang = Barang::with(['batchStok'])->get();

        return response()->json([
            'status' => 'success',
            'data'   => $barang
        ], 200);
    }

    // Menambah data barang baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_sku'     => 'required|string|unique:barang,kode_sku', // [SESUAIKAN: nama_tabel, nama_kolom_sku]
            'nama'         => 'required|string|max:150',
            'kategori'     => 'required|in:Makanan,Minuman,Obat,Kebutuhan Rumah,Lainnya',
            'satuan'       => 'required|string',
            'stok_minimum' => 'required|integer|min:0', // [SESUAIKAN: nama_kolom_stok_minimum]
        ]);

        // Menyimpan ID user yang menambahkan data
        $validated['created_by'] = auth()->id(); // [SESUAIKAN: nama_kolom_created_by]

        $barang = Barang::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Barang berhasil ditambahkan',
            'data'    => $barang
        ], 201);
    }

    // Menampilkan detail 1 barang
    public function show($id)
    {
        $barang = Barang::with(['batchStok'])->find($id); // [SESUAIKAN: nama relasi di Model]

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $barang
        ], 200);
    }

    // Soft delete barang
    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        // Catat user yang menghapus sebelum Soft Delete
        $barang->update([
            'deleted_by' => auth()->id() // [SESUAIKAN: nama_kolom_deleted_by]
        ]);

        $barang->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Barang berhasil dihapus'
        ], 200);
    }
}
