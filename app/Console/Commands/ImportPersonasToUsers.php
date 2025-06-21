<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ImportPersonasToUsers extends Command
{
    protected $signature = 'import:personas';
    protected $description = 'Crea usuarios automáticamente desde la tabla personas';

    public function handle()
    {
        $personas = Persona::all();
        foreach ($personas as $persona) {
            // Verifica si ya existe el usuario
            if (!User::where('email', $persona->email)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->email,
                    'password' => Hash::make($persona->cedula),
                    'role' => $persona->cargo, // Ej: Estudiante, Docente, etc.
                ]);
                $this->info("Usuario creado: {$persona->email}");
            } else {
                $this->info("Ya existe: {$persona->email}");
            }
        }

        return Command::SUCCESS;
    }
}
