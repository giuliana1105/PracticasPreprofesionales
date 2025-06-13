<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cargos')->insert([
            [
             //   'id_cargo' => 1,
                'nombre_cargo' => 'Administrador',
                'siglas_cargo' => 'ADM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
