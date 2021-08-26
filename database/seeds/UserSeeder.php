<?php

use App\Permission;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creo el usuario admin y le asigno el rol
        DB::table('users')->updateOrInsert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@oedon.com',
            'password' => Hash::make('j52{/eGFaYh3?JRp'),
        ]);

        $admin = User::find(1);
        $admin->assignRole('superadmin');  
        

        // Creo que usuarios de prueba

        // Administrativo
        DB::table('users')->updateOrInsert([
            'id' => 2,
            'name' => 'John Doe',
            'email' => 'administrativo@oedon.com',
            'password' => Hash::make('/bpPnV2YbhS^&b2p'),
            'veterinaria_id' => 1,
        ]);

        $usuario = User::findOrFail(2);
        $usuario->assignRole('administrativo');

        // Veterinario
        DB::table('users')->updateOrInsert([
            'id' => 3,
            'name' => 'Doctor Vet',
            'email' => 'veterinario@oedon.com',
            'password' => Hash::make('=Chux#eX7"\&FqG,'),
            'veterinaria_id' => 1,
        ]);

        $usuario = User::findOrFail(3);
        $usuario->assignRole('veterinario');

        // Cliente
        DB::table('users')->updateOrInsert([
            'id' => 4,
            'name' => 'Juan Perez',
            'email' => 'cliente@oedon.com',
            'password' => Hash::make('ufcx?*k9d&D=;~m>'),
            'veterinaria_id' => 1,
        ]);

        $usuario = User::findOrFail(4);
        $usuario->assignRole('cliente');
    }
}
