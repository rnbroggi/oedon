<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VeterinariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('veterinarias')->updateOrInsert([
            'id'        => 1,
            'nombre'    => 'IFTS',
            'direccion' => 'Mansilla 3643',
            'email'     => 'dfts_ifts18_de2@bue.edu.ar',
            'telefono'  => '48232477'
        ]);
    }
}
