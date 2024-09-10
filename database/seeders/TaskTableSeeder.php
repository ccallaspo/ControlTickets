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
        $task1->name = "Cotización con franquicia";
        $task1->save();

        $task2 = new Task;
        $task2->name = "Cotización sin franquicia";
        $task2->save();

        $task3 = new Task;
        $task3->name = "Gestión administrativa";
        $task3->save();

        $task4 = new Task;
        $task4->name = "Gestión técnica";
        $task4->save();

        $task5 = new Task;
        $task5->name = "Facturación";
        $task5->save();
    }
}
