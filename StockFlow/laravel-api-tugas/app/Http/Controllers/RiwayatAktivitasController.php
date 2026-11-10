<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    // List Log Audit (FR-31)
    public function index(Request $request)
    {
        $user = $request->user();
        $query = RiwayatAktivitas::with('user:id,nama,username,peran')
            ->orderBy('waktu', 'desc');

        // Jika peran staf, batasi hanya melihat aktivitas miliknya sendiri (FR-31)
        if ($user->peran === 'staf') {
            $query->where('user_id', $user->id);
        }

        $riwayat = $query->limit(100)->get();

        return response()->json(['status' => 'success', 'data' => $riwayat], 200);
    }
}
