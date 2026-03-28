<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Usuario Admin
        Usuario::create([
            'nombre' => 'Paul Quispe',
            'email' => 'paulquispechoque2018@gmail.com',
            'password' => Hash::make('74545356'),
            'rol' => 'admin',
            'email_verificado' => true,
        ]);

        // Usuario Cliente de prueba
        Usuario::create([
            'nombre' => 'Cliente Prueba',
            'email' => 'cliente@prueba.com',
            'password' => Hash::make('12345678'),
            'rol' => 'cliente',
            'email_verificado' => true,
        ]);
    }
}
