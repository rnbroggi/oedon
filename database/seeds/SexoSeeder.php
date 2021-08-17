<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sexos')->updateOrInsert([
            'id' => 1,
            'nombre' => 'Macho',
        ]);

        DB::table('sexos')->updateOrInsert([
            'id' => 2,
            'nombre' => 'Hembra',
        ]);

        DB::table('sexos')->updateOrInsert([
            'id' => 3,
            'nombre' => 'Indefinido',
        ]);

        DB::table('sexos')->updateOrInsert([
            'id' => 4,
            'nombre' => 'Desconocido',
        ]);
    }
}
