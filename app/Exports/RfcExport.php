<?php

namespace App\Exports;

use App\Models\PermohonanRfc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class RfcExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $opdId;
    
    public function __construct($filters = [])
    {
        $this->startDate = $filters['start_date'] ?? null;
        $this->endDate = $filters['end_date'] ?? null;
        $this->status = $filters['status'] ?? null;
        $this->opdId = $filters['opd_id'] ?? null;
    }
    
    public function collection()
    {
        $query = PermohonanRfc::with(['pengguna', 'opd', 'kategori'])
            ->orderByDesc('created_at');
        
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        
        if ($this->status) {
            $query->where('status_terkini', $this->status);
        }
        
        if ($this->opdId) {
            $query->where('opd_id', $this->opdId);
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
            'Judul Perubahan',
            'Urgensi',
            'Status',
            'Target SLA',
            'Nomor Kontak',
            'Email',
        ];
    }
    
    public function map($rfc): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $rfc->kode_pelacakan,
            $rfc->tanggal_permohonan?->format('d/m/Y'),
            $rfc->nama_pemohon,
            $rfc->nip,
            $rfc->opd?->nama_opd,
            $rfc->unit_kerja,
            $rfc->kategori?->nama_kategori,
            $rfc->judul_perubahan ?? substr($rfc->deskripsi_perubahan ?? '-', 0, 50),
            ucfirst($rfc->urgensi ?? '-'),
            $rfc->status_terkini_label,
            $rfc->target_sla?->format('d/m/Y H:i'),
            $rfc->nomor_kontak,
            $rfc->email_dinas,
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0757a8']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
