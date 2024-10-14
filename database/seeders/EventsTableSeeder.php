<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
    }
}
