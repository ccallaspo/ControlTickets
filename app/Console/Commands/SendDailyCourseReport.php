<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Followup; 
use App\Mail\DailyCourseReportMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class SendDailyCourseReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un correo diario con los cursos que inician y finalizan hoy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Obtener cursos que inician hoy
        $coursesStartingToday = Followup::whereDate('f_star', $today)->get();

        // Obtener cursos que finalizan hoy
        $coursesEndingToday = Followup::whereDate('f_end', $today)->get();

        // Usuario al que se enviará el correo
        $recipient = 'cafutrille@gmail.com';

        // Enviar correo
        Mail::to($recipient)->send(new DailyCourseReportMail($coursesStartingToday, $coursesEndingToday));

        $this->info('Correo enviado correctamente.');
    }
}
