<?php

use Illuminate\Database\Seeder;

class ActivitiesPetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();
        $device_ids = \App\Models\Device::all()->pluck('id')->toArray();
        $pets = \App\Models\Pet::all();
        foreach ($pets as $pet) {
            if($pet->device_id){
                $device_id = $pet->device_id;
            }else{
                $device_id = array_random($device_ids);
            }
        	for ($i=0; $i < 50; $i++) {
        		\App\Models\Jump::create([
        			'amount' => rand(5,20),
			        'device_id' => $device_id,
			        'pet_id' => $pet->id,
			        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
        		]);

        		\App\Models\Nap::create([
        			'amount' => rand(0,10),
			        'device_id' => $device_id,
			        'pet_id' => $pet->id,
			        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
        		]);

        		\App\Models\Roll::create([
        			'amount' => rand(0,100),
			        'device_id' => $device_id,
			        'pet_id' => $pet->id,
			        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
        		]);

        		\App\Models\Smile::create([
        			'amount' => rand(0,100),
			        'device_id' => $device_id,
			        'pet_id' => $pet->id,
			        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
        		]);
        	}
        }
    }
}
