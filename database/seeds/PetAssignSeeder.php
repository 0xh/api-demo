<?php

use Illuminate\Database\Seeder;

class PetAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('pet_assigns')->truncate();
        $pets = \App\Models\Pet::all();
        $company = \App\Models\Company::all()->first();
        $walkers = \App\Models\User::where('role',3)->where('company_id',$company['id'])->first();
        if($walkers){
        	$walker = $walkers['id'];
        }else{
        	$walker = null;
        }
        foreach ($pets as $pet) {
        	\App\Models\PetAssign::create([
				'pet_id' => $pet->id,
				'company_id' => $company['id'],
		        'walker_id' => $walker
			]);
        }
    }
}
