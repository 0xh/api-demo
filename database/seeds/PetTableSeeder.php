<?php

use Illuminate\Database\Seeder;

class PetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('pets')->truncate();
        factory(App\Models\Pet::class, 10)->create();
    }
}
