<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Persona; // Asegúrate de importar el modelo Persona

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::truncate(); // Opcional: limpia la tabla users si quieres empezar de cero
        Persona::truncate(); // Esto vaciará la tabla personas

        $personaData = [
            'cedula' => '1234567890',
            'nombres' => 'Karina Maria',
            'apellidos' => 'Chicaiza Pérez',
            'celular' => '0999999999',
            'email' => 'csoft@utn.edu.ec',
            'carrera_id' => 1, // Debe existir en la tabla 'carreras'
            'cargo' => 'secretario_general',   // Debe existir en la tabla 'cargos'
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insertar persona
        DB::table('personas')->insert($personaData);

        // Crear usuario automáticamente si no existe y el email no es nulo/ vacío
        if (!empty($personaData['email']) && !\App\Models\User::where('email', $personaData['email'])->exists()) {
            \App\Models\User::create([
                'name' => $personaData['nombres'] . ' ' . $personaData['apellidos'],
                'email' => $personaData['email'],
                'password' => Hash::make($personaData['cedula']), // contraseña = cédula
                'cargo' => 'secretario_general', 
                'must_change_password' => true, // <-- agrega esta línea
            ]);
            $this->command->info('Usuario creado: ' . $personaData['email']);
        }
    }
}
