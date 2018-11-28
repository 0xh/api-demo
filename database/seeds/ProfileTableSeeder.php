<?php

use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $walkers = \App\Models\User::where('role',3)->get();
        foreach ($walkers as $walker) {
        	\App\Models\Profile::create([
				'user_id' => $walker->id,
		        'name' => 'walker-id-'.$walker->id,
			]);
        }
    }
}
