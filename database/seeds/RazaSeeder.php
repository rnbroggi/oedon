<?php

use App\Animal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $animales = Animal::pluck('id')->toArray();

        foreach ($animales as $index => $animal_id){
            DB::table('razas')->updateOrInsert([
                'id' => $index + 1,
                'nombre' => 'Otro',
                'animal_id' => $animal_id
            ]);
        }
    }
}
