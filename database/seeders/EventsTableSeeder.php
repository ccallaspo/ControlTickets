<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event1 = new Event();
        $event1->name = "Cotización enviada";
        $event1->icono = "heroicon-o-rocket-launch";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Cotización aprobada";
        $event1->icono = "heroicon-o-check-badge";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Curso agendado";
        $event1->icono = "heroicon-o-calendar";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Curso matriculado";
        $event1->icono = "heroicon-o-user-group";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Curso en proceso";
        $event1->icono = "heroicon-o-play";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Curso finalizado";
        $event1->icono = "heroicon-o-check-circle";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "DJ OTEC generada";
        $event1->icono = "heroicon-o-document-text";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "DJs generadas";
        $event1->icono = "heroicon-o-document";
        $event1->task_id = 1;        
        $event1->save();

        $event1 = new Event();
        $event1->name = "Por facturar";
        $event1->icono = "heroicon-o-currency-dollar";
        $event1->task_id = 1;        
        $event1->save();
    }
}
