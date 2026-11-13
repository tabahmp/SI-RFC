<?php

namespace App\Console\Commands;

use App\Exports\RfcDetailExport;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateMonthlyReportCommand extends Command
{
    protected $signature = 'report:monthly {--month= : Month (YYYY-MM format)} {--email= : Override recipient email}';
    protected $description = 'Generate monthly RFC report and send via email to admin';
    
    public function handle()
    {
        $this->info('Generating monthly RFC report...');
        
        // Determine month
        $month = $this->option('month') 
            ? \Carbon\Carbon::parse($this->option('month') . '-01')
            : now()->subMonth();
        
        $startDate = $month->copy()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();
        
        $this->info("Report period: {$startDate->format('d M Y')} - {$endDate->format('d M Y')}");
        
        // Generate Excel file
        $filename = 'laporan-bulanan-rfc-' . $month->format('Y-m') . '.xlsx';
        $filepath = 'reports/' . $filename;
        
        $filters = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
        
        Excel::store(new RfcDetailExport($filters), $filepath, 'local');
        
        $this->info("Report generated: storage/app/{$filepath}");
        
        // Send email to admins
        $recipients = $this->option('email')
            ? [$this->option('email')]
            : User::whereHas('peran', fn($q) => $q->where('nama_peran', 'admin'))
                ->pluck('email')
                ->toArray();
        
        if (empty($recipients)) {
            $this->warn('No admin email found. Skipping email notification.');
            Log::warning('Monthly report generated but no admin emails to send to.');
            return Command::SUCCESS;
        }
        
        foreach ($recipients as $email) {
            try {
                Mail::send('emails.monthly-report', [
                    'month' => $month->translatedFormat('F Y'),
                    'startDate' => $startDate->format('d/m/Y'),
                    'endDate' => $endDate->format('d/m/Y'),
                    'filename' => $filename,
                ], function($message) use ($email, $month, $filepath) {
                    $message->to($email)
                        ->subject('[SI-RFC] Laporan Bulanan RFC - ' . $month->translatedFormat('F Y'))
                        ->attach(storage_path('app/' . $filepath));
                });
                
                $this->info("Report sent to: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$email}: " . $e->getMessage());
                Log::error("Monthly report email failed", [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        Log::info('Monthly RFC report generated and sent', [
            'period' => $month->format('Y-m'),
            'recipients' => count($recipients),
            'file' => $filepath
        ]);
        
        $this->info('Monthly report generation complete!');
        
        return Command::SUCCESS;
    }
}
