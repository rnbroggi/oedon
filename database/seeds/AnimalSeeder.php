<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('animales')->updateOrInsert([
            'id' => 1,
            'nombre' => 'Perro',
        ]);

        DB::table('animales')->updateOrInsert([
            'id' => 2,
            'nombre' => 'Gato',
        ]);

        DB::table('animales')->updateOrInsert([
            'id' => 3,
            'nombre' => 'Pájaro',
        ]);

        DB::table('animales')->updateOrInsert([
            'id' => 4,
            'nombre' => 'Pez',
        ]);

        DB::table('animales')->updateOrInsert([
            'id' => 5,
            'nombre' => 'Hamster',
        ]);

        DB::table('animales')->updateOrInsert([
            'id' => 6,
            'nombre' => 'Otro',
        ]);
    }
}
