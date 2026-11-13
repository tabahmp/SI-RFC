<?php

namespace App\Http\Controllers;

use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Get RFC statistics by status
     */
    public function rfcByStatus(Request $request)
    {
        $cacheKey = 'analytics.rfc_by_status.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $query = PermohonanRfc::select('status_terkini', DB::raw('count(*) as total'))
                ->groupBy('status_terkini');
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            if ($request->filled('opd_id')) {
                $query->where('opd_id', $request->opd_id);
            }
            
            $data = $query->get();
            
            $statusLabels = [
                'draf' => 'Draf',
                'diajukan' => 'Diajukan',
                'perlu_revisi' => 'Perlu Revisi',
                'ditolak_verifikator' => 'Ditolak Verifikator',
                'menunggu_persetujuan' => 'Menunggu Persetujuan',
                'ditolak_pimpinan' => 'Ditolak Pimpinan',
                'ditunda' => 'Ditunda',
                'disetujui' => 'Disetujui',
                'sedang_dikerjakan' => 'Sedang Dikerjakan',
                'selesai' => 'Selesai',
            ];
            
            return [
                'labels' => $data->pluck('status_terkini')->map(fn($s) => $statusLabels[$s] ?? $s)->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }
    
    /**
     * Get RFC statistics by urgency
     */
    public function rfcByUrgency(Request $request)
    {
        $cacheKey = 'analytics.rfc_by_urgency.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $query = PermohonanRfc::select('urgensi', DB::raw('count(*) as total'))
                ->groupBy('urgensi');
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $data = $query->get();
            
            $urgencyLabels = [
                'rendah' => 'Rendah',
                'sedang' => 'Sedang',
                'tinggi' => 'Tinggi',
                'kritis' => 'Kritis',
            ];
            
            return [
                'labels' => $data->pluck('urgensi')->map(fn($u) => $urgencyLabels[$u] ?? $u)->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }
    
    /**
     * Get RFC trend over time (monthly)
     */
    public function rfcTrend(Request $request)
    {
        $cacheKey = 'analytics.rfc_trend.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $months = $request->input('months', 6);
            
            $data = PermohonanRfc::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths($months))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return [
                'labels' => $data->pluck('month')->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }
    
    /**
     * Get RFC statistics by category
     */
    public function rfcByCategory(Request $request)
    {
        $cacheKey = 'analytics.rfc_by_category.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $query = PermohonanRfc::select('kategori_rfc.nama_kategori', DB::raw('count(permohonan_rfc.id) as total'))
                ->join('kategori_rfc', 'permohonan_rfc.kategori_id', '=', 'kategori_rfc.id')
                ->groupBy('kategori_rfc.nama_kategori');
            
            if ($request->filled('start_date')) {
                $query->whereDate('permohonan_rfc.created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('permohonan_rfc.created_at', '<=', $request->end_date);
            }
            
            $data = $query->get();
            
            return [
                'labels' => $data->pluck('nama_kategori')->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }
    
    /**
     * Get RFC statistics by OPD
     */
    public function rfcByOpd(Request $request)
    {
        $cacheKey = 'analytics.rfc_by_opd.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $limit = $request->input('limit', 10);
            
            $query = PermohonanRfc::select('opd.nama_opd', DB::raw('count(permohonan_rfc.id) as total'))
                ->join('opd', 'permohonan_rfc.opd_id', '=', 'opd.id')
                ->groupBy('opd.nama_opd')
                ->orderByDesc('total')
                ->limit($limit);
            
            if ($request->filled('start_date')) {
                $query->whereDate('permohonan_rfc.created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('permohonan_rfc.created_at', '<=', $request->end_date);
            }
            
            $data = $query->get();
            
            return [
                'labels' => $data->pluck('nama_opd')->toArray(),
                'data' => $data->pluck('total')->toArray(),
            ];
        });
    }
    
    /**
     * Get completion rate statistics
     */
    public function completionRate(Request $request)
    {
        $cacheKey = 'analytics.completion_rate.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $query = PermohonanRfc::query();
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $total = $query->count();
            $selesai = (clone $query)->where('status_terkini', 'selesai')->count();
            $ditolak = (clone $query)->whereIn('status_terkini', ['ditolak_verifikator', 'ditolak_pimpinan'])->count();
            $aktif = (clone $query)->whereNotIn('status_terkini', ['selesai', 'ditolak_verifikator', 'ditolak_pimpinan', 'draf'])->count();
            $draf = (clone $query)->where('status_terkini', 'draf')->count();
            
            return [
                'total' => $total,
                'selesai' => $selesai,
                'ditolak' => $ditolak,
                'aktif' => $aktif,
                'draf' => $draf,
                'completion_percentage' => $total > 0 ? round(($selesai / $total) * 100, 2) : 0,
                'rejection_percentage' => $total > 0 ? round(($ditolak / $total) * 100, 2) : 0,
            ];
        });
    }
    
    /**
     * Get SLA compliance statistics
     */
    public function slaCompliance(Request $request)
    {
        $cacheKey = 'analytics.sla_compliance.' . md5(json_encode($request->all()));
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
            $query = PermohonanRfc::whereNotNull('target_sla');
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $total = $query->count();
            $onTime = (clone $query)->where('status_terkini', 'selesai')
                ->whereColumn('updated_at', '<=', 'target_sla')
                ->count();
            $overdue = (clone $query)->where('status_terkini', 'selesai')
                ->whereColumn('updated_at', '>', 'target_sla')
                ->count();
            $pending = (clone $query)->whereNotIn('status_terkini', ['selesai', 'ditolak_verifikator', 'ditolak_pimpinan'])
                ->where('target_sla', '<', now())
                ->count();
            
            return [
                'total' => $total,
                'on_time' => $onTime,
                'overdue' => $overdue,
                'pending_overdue' => $pending,
                'compliance_percentage' => $total > 0 ? round(($onTime / $total) * 100, 2) : 0,
            ];
        });
    }
    
    /**
     * Get dashboard summary statistics
     */
    public function dashboardSummary(Request $request)
    {
        $cacheKey = 'analytics.dashboard_summary.' . ($request->user()->id ?? 'guest');
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            $user = $request->user();
            $role = $user->peran?->nama_peran ?? $user->role ?? '';
            
            $summary = [
                'total_rfc' => PermohonanRfc::count(),
                'pending_approval' => PermohonanRfc::where('status_terkini', 'menunggu_persetujuan')->count(),
                'in_progress' => PermohonanRfc::where('status_terkini', 'sedang_dikerjakan')->count(),
                'completed_this_month' => PermohonanRfc::where('status_terkini', 'selesai')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
                'overdue' => PermohonanRfc::whereNotNull('target_sla')
                    ->whereNotIn('status_terkini', ['selesai', 'ditolak_verifikator', 'ditolak_pimpinan'])
                    ->where('target_sla', '<', now())
                    ->count(),
            ];
            
            // Role-specific stats
            if ($role === 'pemohon') {
                $summary['my_rfc'] = PermohonanRfc::where('pengguna_id', $user->id)->count();
                $summary['my_pending'] = PermohonanRfc::where('pengguna_id', $user->id)
                    ->whereNotIn('status_terkini', ['selesai', 'ditolak_verifikator', 'ditolak_pimpinan'])
                    ->count();
            } elseif ($role === 'verifikator') {
                $summary['pending_verification'] = PermohonanRfc::where('status_terkini', 'diajukan')->count();
            } elseif ($role === 'teknisi') {
                $summary['assigned_to_me'] = PermohonanRfc::whereHas('eksekusiAktif', function($q) use ($user) {
                    $q->where('teknisi_id', $user->id);
                })->count();
            }
            
            return $summary;
        });
    }
    
    /**
     * Clear analytics cache
     */
    public function clearCache()
    {
        Cache::tags(['analytics'])->flush();
        
        return response()->json(['message' => 'Cache berhasil dibersihkan']);
    }
}
