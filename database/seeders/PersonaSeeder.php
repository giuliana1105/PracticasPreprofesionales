<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personas')->insert([
            'cedula' => '1234567890',
            'nombres' => 'Irma Marilú',
            'apellidos' => 'Basantes Cevallos',
            'celular' => '0999999999',
            'correo' => 'admin@ejemplo.com',
            'carrera_id' => 1, // Debe existir en la tabla 'carreras'
            'cargo_id' => 1,   // Debe existir en la tabla 'cargos'
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
