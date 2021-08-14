<?php

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
        $roles = ['superadmin', 'administrativo', 'veterinario', 'cliente'];

        foreach ($roles as $role_name) {
            $r = Role::updateOrCreate(['name' => $role_name]);
            $r->givePermissionTo('home');
        }
    }
}
