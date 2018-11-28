<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->truncate();
        $faker = Faker\Factory::create();
        $companies = \App\Models\User::where('role',2)->get();
        foreach ($companies as $company) {
        	\App\Models\Company::create([
    			'name' => 'company-'.$faker->text(20),
		        'address' => $faker->address,
		        'city_id' => rand(1,10),
		        'country_id' => rand(1,10),
		        'user_id' => $company->id,
		        'postal' => '32423355532',
		        'description' => $faker->text(50),
    		]);
        }
    }
}
