<?php

namespace App\Exports;

use App\Models\PermohonanRfc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RfcDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    
    public function __construct($filters = [])
    {
        $this->startDate = $filters['start_date'] ?? null;
        $this->endDate = $filters['end_date'] ?? null;
    }
    
    public function collection()
    {
        $query = PermohonanRfc::with(['pengguna', 'opd', 'kategori', 'histories'])
            ->orderByDesc('created_at');
        
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Kode Pelacakan',
            'Tanggal Pengajuan',
            'Nama Pemohon',
            'NIP',
            'OPD',
            'Unit Kerja',
            'Kategori',
            'Jenis Perubahan',
            'Deskripsi Perubahan',
            'Latar Belakang',
            'Urgensi',
            'Status',
            'Target SLA',
            'Durasi Proses (hari)',
            'History Count',
        ];
    }
    
    public function map($rfc): array
    {
        static $no = 0;
        $no++;
        
        $durasiProses = null;
        if ($rfc->status_terkini === 'selesai') {
            $durasiProses = $rfc->created_at->diffInDays($rfc->updated_at);
        }
        
        return [
            $no,
            $rfc->kode_pelacakan,
            $rfc->tanggal_permohonan?->format('d/m/Y'),
            $rfc->nama_pemohon,
            $rfc->nip,
            $rfc->opd?->nama_opd,
            $rfc->unit_kerja,
            $rfc->kategori?->nama_kategori,
            is_array($rfc->jenis_perubahan) ? implode(', ', $rfc->jenis_perubahan) : '-',
            substr($rfc->deskripsi_perubahan ?? '-', 0, 200),
            substr($rfc->latar_belakang ?? '-', 0, 200),
            ucfirst($rfc->urgensi ?? '-'),
            $rfc->status_terkini_label,
            $rfc->target_sla?->format('d/m/Y H:i'),
            $durasiProses,
            $rfc->histories->count(),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '16834A']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
