<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Followup;
use App\Mail\DailyOperationsReportMail; 
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyOperationsReport extends Command
{
    protected $signature = 'report:daily-courses-operations'; 

    protected $description = 'Envia reporte a Operaciones priorizando datos de ejecución';

    public function handle()
    {
        $today = Carbon::today();

        $coursesStartingToday = Followup::query()
            ->where(function ($query) use ($today) {
                $query->where('has_execution_data', true)
                      ->whereDate('exec_f_star', $today);
            })
            ->orWhere(function ($query) use ($today) {
                $query->where('has_execution_data', false) 
                      ->whereDate('f_star', $today);
            })
            ->get();

        $coursesEndingToday = Followup::query()
            ->where(function ($query) use ($today) {
                $query->where('has_execution_data', true)
                      ->whereDate('exec_f_end', $today);
            })
            ->orWhere(function ($query) use ($today) {
                $query->where('has_execution_data', false)
                      ->whereDate('f_end', $today);
            })
            ->get();

        $recipients = ['coordinacion@otecproyecta.cl', 'contacto@otecproyecta.cl']; 
        $bccRecipient = 'cafutrille@gmail.com';

        Mail::to($recipients)
            ->bcc($bccRecipient)
            ->send(new DailyOperationsReportMail($coursesStartingToday, $coursesEndingToday));

        $this->info('Reporte de Operaciones enviado correctamente.');
    }
}