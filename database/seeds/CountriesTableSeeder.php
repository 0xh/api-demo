<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('countries')->truncate();
        $countries = require base_path('/database/seeds/dumps/countries.php');

        foreach ($countries as $c) {
            \App\Models\Country::create([
                'code' => $c['code'],
                'name' => $c['name'],
            ]);
        }
    }
}
