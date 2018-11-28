<?php

use Illuminate\Database\Seeder;

class InfoPetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// DB::table('jumps')->truncate();
    	// DB::table('naps')->truncate();
    	// DB::table('rolls')->truncate();
    	// DB::table('smiles')->truncate();
        // Jumps
        for ($i=0; $i < 40; $i++) { 
        	\App\Models\Jump::create([
        		'amount' => rand(0,10),
		        'device_id' => 19,
		        'pet_id' => 13
        	]);

        	\App\Models\Nap::create([
        		'amount' => rand(0,10),
		        'device_id' => 19,
		        'pet_id' => 13
        	]);

        	\App\Models\Roll::create([
        		'amount' => rand(0,10),
		        'device_id' => 19,
		        'pet_id' => 13
        	]);

        	\App\Models\Smile::create([
        		'amount' => rand(0,10),
		        'device_id' => 19,
		        'pet_id' => 13
        	]);

        	sleep(10);
        }
    }
}
