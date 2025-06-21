<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashPlainUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:hash-plain-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hashea los passwords en texto plano de los usuarios (por ejemplo, cédula) usando bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $updated = 0;

        foreach ($users as $user) {
            // Solo hashear si la contraseña NO está en formato bcrypt ($2y$, $2a$, $2b$)
            if (!preg_match('/^\\$2[aby]\\$/', substr($user->password, 0, 4))) {
                $user->password = Hash::make($user->password);
                $user->save();
                $updated++;
            }
        }

        $this->info("Contraseñas actualizadas: $updated");
    }
}
