<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carreras')->insert([
            [
               // 'id_carrera' => 1,
                'nombre_carrera' => 'Software',
                'siglas_carrera' => 'CSOFT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
