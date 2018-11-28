<?php

use Illuminate\Database\Seeder;

class DataTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('devices')->truncate();
    	DB::table('pets')->truncate();
    	DB::table('locations')->truncate();
        $faker = Faker\Factory::create();
        $users = \App\Models\User::all();

        foreach ($users as $user) {
        	// create device for user
        	for($i=1; $i <= 5; $i++){
        		$device = \App\Models\Device::create([
	                'imei' => str_random(15),
			        'name' => $faker->text(10),
			        'user_id' => $user->id,
			        'battery' => rand(10,100),
			        'phone' => $faker->phoneNumber,
			        'mode' => rand(0,1),
			        'company_id' => rand(1,5),
			        'product_id' => rand(1,50)
				]);

        		// create pet for user
				\App\Models\Pet::create([
        			'name' => $faker->name,
			        'description' => $faker->text(50),
			        'device_id' => $device->id,
			        'breed_id' => rand(2,3),
			        'animal_id' => 3,
			        'user_id' => $user->id
        		]);

				// create location for device
        		\App\Models\Location::create([
	                'device_id' => $device->id,
	                'user_id' => $user->id,
	                'lat' => $faker->latitude(),
	                'long' => $faker->longitude()
	            ]);
        	}
        }


        // update product for device
        $products = \App\Models\Product::all();
        foreach ($products as $product) {
        	$device = \App\Models\Device::find($product->id);
        	if($device){
        		$device->product_id = $product->id;
        		$device->save();
        	}
        }

    }
}
