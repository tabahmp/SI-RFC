<?php

namespace App\Console\Commands;

use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GenerateMonthlyReportCommand extends Command
{
    protected $signature = 'report:monthly {--month= : Month (YYYY-MM format)} {--email= : Override recipient email}';
    protected $description = 'Generate monthly RFC report and send via email to admin';

    public function handle()
    {
        $this->info('Generating monthly RFC report...');

        $month     = $this->option('month')
            ? \Carbon\Carbon::parse($this->option('month') . '-01')
            : now()->subMonth();

        $startDate = $month->copy()->startOfMonth();
        $endDate   = $month->copy()->endOfMonth();

        $this->info("Report period: {$startDate->format('d M Y')} - {$endDate->format('d M Y')}");

        // Gather statistics
        $total   = PermohonanRfc::whereBetween('created_at', [$startDate, $endDate])->count();
        $selesai = PermohonanRfc::where('status_terkini', 'selesai')->whereBetween('created_at', [$startDate, $endDate])->count();

        $recipients = $this->option('email')
            ? [$this->option('email')]
            : User::where('role', 'admin')->pluck('email')->toArray();

        if (empty($recipients)) {
            $this->warn('No admin email found. Skipping.');
            return Command::SUCCESS;
        }

        foreach ($recipients as $email) {
            try {
                Mail::send('emails.monthly-report', [
                    'month'      => $month->translatedFormat('F Y'),
                    'startDate'  => $startDate->format('d/m/Y'),
                    'endDate'    => $endDate->format('d/m/Y'),
                    'total'      => $total,
                    'selesai'    => $selesai,
                ], function ($msg) use ($email, $month) {
                    $msg->to($email)
                        ->subject('[SI-RFC] Laporan Bulanan RFC - ' . $month->translatedFormat('F Y'));
                });
                $this->info("Report sent to: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to send to {$email}: " . $e->getMessage());
                Log::error('Monthly report email failed', ['email' => $email, 'error' => $e->getMessage()]);
            }
        }

        $this->info('Monthly report complete!');
        return Command::SUCCESS;
    }
}
