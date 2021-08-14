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
        Role::updateOrCreate(['name' => 'superadmin']);
        Role::updateOrCreate(['name' => 'administrativo']);
        Role::updateOrCreate(['name' => 'veterinario']);
        Role::updateOrCreate(['name' => 'cliente']);
    }
}
