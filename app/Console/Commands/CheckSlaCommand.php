<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSlaCommand extends Command
{
    protected $signature = 'rfc:check-sla';
    protected $description = 'Check RFC SLA and send alerts for approaching deadlines';
    
    public function handle()
    {
        $this->info('Checking RFC SLA...');
        
        // RFC with SLA < 4 hours (critical)
        $criticalRfcs = PermohonanRfc::whereIn('status_terkini', ['diajukan', 'menunggu_persetujuan', 'disetujui'])
            ->whereNotNull('target_sla')
            ->where('target_sla', '<=', now()->addHours(4))
            ->where('target_sla', '>', now())
            ->get();
        
        foreach ($criticalRfcs as $rfc) {
            $this->alertSla($rfc, 'critical');
        }
        
        // Overdue RFC
        $overdueRfcs = PermohonanRfc::whereIn('status_terkini', ['diajukan', 'menunggu_persetujuan', 'disetujui'])
            ->whereNotNull('target_sla')
            ->where('target_sla', '<', now())
            ->get();
        
        foreach ($overdueRfcs as $rfc) {
            $this->alertSla($rfc, 'overdue');
        }
        
        $this->info(sprintf('SLA check complete. Critical: %d, Overdue: %d', $criticalRfcs->count(), $overdueRfcs->count()));
        
        Log::channel('stack')->info('SLA check completed', [
            'critical' => $criticalRfcs->count(),
            'overdue' => $overdueRfcs->count(),
        ]);
        
        return Command::SUCCESS;
    }
    
    private function alertSla(PermohonanRfc $rfc, string $severity)
    {
        // Determine recipients based on status
        $notifyUsers = [];
        
        if ($rfc->status_terkini === 'diajukan') {
            $notifyUsers = User::whereHas('peran', fn($q) => $q->where('nama_peran', 'verifikator'))->get();
        } elseif ($rfc->status_terkini === 'menunggu_persetujuan') {
            $notifyUsers = User::whereHas('peran', fn($q) => $q->where('nama_peran', 'pimpinan'))->get();
        } elseif ($rfc->status_terkini === 'disetujui') {
            $eksekusi = $rfc->eksekusiAktif;
            if ($eksekusi && $eksekusi->teknisi) {
                $notifyUsers = [$eksekusi->teknisi];
            }
        }
        
        $message = $severity === 'critical' 
            ? sprintf('RFC %s mendekati batas SLA (< 4 jam)', $rfc->kode_pelacakan)
            : sprintf('RFC %s telah melewati batas SLA', $rfc->kode_pelacakan);
        
        foreach ($notifyUsers as $user) {
            // Check if already notified today
            $existing = Notifikasi::where('pengguna_id', $user->id)
                ->where('rfc_id', $rfc->id)
                ->where('tipe', 'sla_alert')
                ->whereDate('dibuat_pada', today())
                ->exists();
            
            if (!$existing) {
                Notifikasi::create([
                    'pengguna_id' => $user->id,
                    'rfc_id' => $rfc->id,
                    'tipe' => 'sla_alert',
                    'judul' => $severity === 'critical' ? 'Peringatan SLA' : 'SLA Terlewati',
                    'pesan' => $message,
                    'data_tambahan' => [
                        'severity' => $severity,
                        'target_sla' => $rfc->target_sla->toIso8601String(),
                    ],
                ]);
                
                $this->line("  → Alert sent to {$user->name} for RFC {$rfc->kode_pelacakan}");
            }
        }
    }
}
