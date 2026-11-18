<?php

namespace App\Http\Controllers;

use App\Models\KategoriRfc;
use App\Models\Opd;
use App\Models\PermohonanRfc;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanRfc::with(['pengguna', 'opd', 'kategori', 'persetujuan'])
            ->orderByDesc('created_at');

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter lainnya
        if ($request->filled('opd_id'))       $query->where('opd_id', $request->opd_id);
        if ($request->filled('status'))        $query->where('status_terkini', $request->status);
        if ($request->filled('kategori_id'))   $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('urgensi'))       $query->where('urgensi', $request->urgensi);

        $rfc = $query->paginate(25)->through(fn ($r) => $this->formatRfcLaporan($r));

        // Statistik ringkas
        $baseQuery = PermohonanRfc::query();
        $total      = $baseQuery->count();
        $selesai    = PermohonanRfc::where('status_terkini', 'selesai')->count();
        $bulanIni   = PermohonanRfc::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count();

        return Inertia::render('Admin/Laporan', [
            'rfc'          => $rfc,
            'filterAktif'  => $request->only(['tanggal_dari', 'tanggal_sampai', 'opd_id', 'status', 'kategori_id', 'urgensi']),
            'statistik'    => [
                'total'       => $total,
                'selesai'     => $selesai,
                'bulan_ini'   => $bulanIni,
                'persen_selesai' => $total > 0 ? round(($selesai / $total) * 100) : 0,
            ],
            'opdList'      => Opd::aktif()->orderBy('nama_opd')->get(['id', 'nama_opd']),
            'kategoriList' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori']),
        ]);
    }

    /**
     * Export RFC data to Excel
     */
    public function ekspor(Request $request)
    {
        // Always export as CSV (Excel dependency removed)
        return $this->eksporCsv($request);
    }
    
    /**
     * Legacy CSV export method
     */
    private function eksporCsv(Request $request)
    {
        $query = PermohonanRfc::with(['pengguna', 'opd', 'kategori', 'persetujuan'])
            ->orderByDesc('created_at');

        if ($request->filled('tanggal_dari'))  $query->whereDate('created_at', '>=', $request->tanggal_dari);
        if ($request->filled('tanggal_sampai')) $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        if ($request->filled('opd_id'))         $query->where('opd_id', $request->opd_id);
        if ($request->filled('status'))         $query->where('status_terkini', $request->status);
        if ($request->filled('kategori_id'))    $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('urgensi'))        $query->where('urgensi', $request->urgensi);

        $rfcList = $query->get();

        $filename = 'laporan-rfc-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rfcList) {
            $output = fopen('php://output', 'w');

            // BOM untuk Excel UTF-8
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($output, [
                'No', 'Kode RFC', 'Pemohon', 'OPD', 'Kategori',
                'Urgensi', 'Status', 'Tanggal Ajuan', 'Tanggal Selesai', 'Durasi Proses',
            ]);

            foreach ($rfcList as $i => $rfc) {
                $selesaiPada   = $rfc->status_terkini === 'selesai' ? $rfc->updated_at : null;
                $durasiTeks    = $selesaiPada
                    ? $this->formatDurasi($rfc->created_at, $selesaiPada)
                    : '-';

                fputcsv($output, [
                    $i + 1,
                    $rfc->kode_pelacakan,
                    $rfc->nama_pemohon,
                    $rfc->opd?->nama_opd ?? '-',
                    $rfc->kategori?->nama_kategori ?? '-',
                    $rfc->urgensi_label,
                    $rfc->status_terkini_label,
                    $rfc->created_at->format('d/m/Y'),
                    $selesaiPada?->format('d/m/Y') ?? '-',
                    $durasiTeks,
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Format durasi menjadi teks yang mudah dibaca.
     * mis: "< 1 jam", "3 jam 20 menit", "2 hari 4 jam"
     */
    private function formatDurasi(\Carbon\Carbon $dari, \Carbon\Carbon $sampai): string
    {
        $totalMenit = (int) round($dari->diffInMinutes($sampai));

        if ($totalMenit < 1) {
            return '< 1 menit';
        }

        if ($totalMenit < 60) {
            return "{$totalMenit} menit";
        }

        $jam = intdiv($totalMenit, 60);

        if ($jam < 24) {
            $sisa = $totalMenit % 60;
            return $sisa > 0 ? "{$jam} jam {$sisa} menit" : "{$jam} jam";
        }

        $hari = intdiv($jam, 24);
        $sisaJam = $jam % 24;
        return $sisaJam > 0 ? "{$hari} hari {$sisaJam} jam" : "{$hari} hari";
    }

    private function formatRfcLaporan(PermohonanRfc $rfc): array
    {
        $selesaiPada = $rfc->status_terkini === 'selesai' ? $rfc->updated_at : null;

        return [
            'id'              => $rfc->id,
            'kode_pelacakan'  => $rfc->kode_pelacakan,
            'nama_pemohon'    => $rfc->nama_pemohon,
            'opd'             => $rfc->opd?->nama_opd ?? '-',
            'kategori'        => $rfc->kategori?->nama_kategori ?? '-',
            'urgensi'         => $rfc->urgensi,
            'urgensi_label'   => $rfc->urgensi_label,
            'status_terkini'  => $rfc->status_terkini,
            'status_label'    => $rfc->status_terkini_label,
            'warna_badge'     => $rfc->warna_badge,
            'tanggal_ajuan'   => $rfc->created_at->format('d/m/Y'),
            'tanggal_selesai' => $selesaiPada?->format('d/m/Y'),
            'durasi_teks'     => $selesaiPada
                ? $this->formatDurasi($rfc->created_at, $selesaiPada)
                : null,
        ];
    }
}
