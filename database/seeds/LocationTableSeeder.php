<?php

use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $locations = \App\Models\Location::all();

        foreach ($locations as $l) {
        	$location = \App\Models\Location::find($l->id);
        	$location->lat = $faker->latitude(43.726337, 43.811325);
        	$location->long = $faker->longitude(-79.831782, -79.257876);
        	$location->save();
        }
    }
}
