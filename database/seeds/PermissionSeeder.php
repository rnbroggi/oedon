<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'audit-logs', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'home', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud usuarios', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud roles', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud permisos', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'impersonate', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud veterinarias', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'view clientes', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud razas', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud mascotas', 'guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'crud visitas', 'guard_name' => 'web']);
    }
}
