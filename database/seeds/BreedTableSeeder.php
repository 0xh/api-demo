<?php

use Illuminate\Database\Seeder;

class BreedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('breeds')->truncate();
        factory(App\Models\Breed::class, 10)->create();
    }
}
