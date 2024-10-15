<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Role1 = new Role();
        $Role1->name = "Admin";
        $Role1->save();

        $Role2 = new Role();
        $Role2->name = "Cotizador";
        $Role2->save();

        $Role3 = new Role();
        $Role3->name = "Administrativo";
        $Role3->save();

        $Role4 = new Role();
        $Role4->name = "Facturador";
        $Role4->save();

        $Role5 = new Role();
        $Role5->name = "Soporte";
        $Role5->save();


    }
}
