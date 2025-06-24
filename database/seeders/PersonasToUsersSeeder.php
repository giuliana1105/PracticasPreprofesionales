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
        $count = 0;
        foreach ($personas as $persona) {
            if (!empty($persona->email) && !User::where('email', $persona->email)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->email,
                    'password' => Hash::make($persona->cedula), // La contraseña será la cédula
                    'must_change_password' => true, // <-- agrega esta línea
                ]);
                $count++;
            }
        }
        $this->command->info("Usuarios creados: $count");
    }
}
