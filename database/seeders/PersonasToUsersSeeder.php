<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonasToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = Persona::all();
        foreach ($personas as $persona) {
            if (!User::where('email', $persona->correo)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->correo,
                    'password' => Hash::make($persona->cedula), // La contraseña será la cédula
                ]);
            }
        }
    }
}
