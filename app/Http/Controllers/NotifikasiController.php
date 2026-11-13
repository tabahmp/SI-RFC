<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $pengguna = auth()->user();

        $notifikasi = Notifikasi::where('pengguna_id', $pengguna->id)
            ->orderByDesc('dibuat_pada')
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id'           => $n->id,
                'judul'        => $n->judul,
                'pesan'        => $n->pesan,
                'sudah_dibaca' => $n->sudah_dibaca,
                'url_tujuan'   => $n->url_tujuan,
                'dibuat_pada'  => $n->dibuat_pada?->diffForHumans(),
            ]);

        return response()->json([
            'notifikasi'       => $notifikasi,
            'belum_dibaca'     => Notifikasi::where('pengguna_id', $pengguna->id)
                                             ->where('sudah_dibaca', false)
                                             ->count(),
        ]);
    }

    public function tandaiBaca(int $id)
    {
        $notifikasi = Notifikasi::where('id', $id)
            ->where('pengguna_id', auth()->id())
            ->firstOrFail();

        $notifikasi->update(['sudah_dibaca' => true]);

        return response()->json(['message' => 'Notifikasi ditandai sudah dibaca.']);
    }

    public function tandaiBacaSemua()
    {
        Notifikasi::where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return response()->json(['message' => 'Semua notifikasi ditandai sudah dibaca.']);
    }
}
