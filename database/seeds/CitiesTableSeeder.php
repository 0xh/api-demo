<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->truncate();
        $cities = require base_path('/database/seeds/dumps/cities.php');

        foreach ($cities as $c) {
            \App\Models\City::create([
                'country_code' => $c['country_code'],
                'name' => $c['name'],
            ]);
        }
    }
}
