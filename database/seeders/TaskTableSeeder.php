<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task1 = new Task;
        $task1->name = "Cotización";
        $task1->save();

        $this->call(EventsTableSeeder::class);
    }
}
