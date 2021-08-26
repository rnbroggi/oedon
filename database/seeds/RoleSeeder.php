<?php

use App\Permission;
use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Superadmin
        $admin = Role::updateOrCreate(['name' => 'superadmin']);
        $all_permissions = Permission::pluck('name')->toArray();
        $admin->givePermissionTo($all_permissions);

        // Permisos del administrativo
        $r = Role::updateOrCreate(['name' => 'administrativo']);
        $r->givePermissionTo(['home', 'crud usuarios', 'list usuarios', 'view clientes', 'crud mascotas', 'crud visitas', 'view visitas']);

        // Permisos del veterinario
        $r = Role::updateOrCreate(['name' => 'veterinario']);
        $r->givePermissionTo(['home', 'crud usuarios', 'view clientes', 'crud mascotas', 'crud visitas', 'view visitas']);

        // Permisos del cliente
        $r->givePermissionTo(['home', 'view visitas', 'view mascotas']);
        $r = Role::updateOrCreate(['name' => 'cliente']);
    }
}
