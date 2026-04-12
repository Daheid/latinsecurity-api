<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos firstOrCreate para que no dé error si lo ejecutas dos veces
        User::firstOrCreate(
            ['email' => 'admin@latinosecurity.com'], // Busca por este email
            [
                'name' => 'Administrador',
                'password' => bcrypt('latinosecurity2026@') // Si no existe, lo crea con esta contraseña
            ]
        );
    }
}
