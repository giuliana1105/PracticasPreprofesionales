<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero seeders de tablas relacionadas
        $this->call([
            CarreraSeeder::class,
            PersonaSeeder::class,
            PersonasToUsersSeeder::class,
            // Elimina o comenta cualquier referencia a CargoSeeder
        ]);

        // Si quieres mantener este usuario de prueba, puedes dejarlo:
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
