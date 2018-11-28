<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        \App\Models\User::create([
			'email' => 'admin@gmail.com',
	        'password' => bcrypt(123123),
	        'company_id' => null,
	        'role'=> 1
		]);
        for ($i=1; $i <= 10; $i++) {
    		\App\Models\User::create([
    			'email' => 'company_'.$i.'@gmail.com',
		        'password' => bcrypt(123123),
		        'company_id' => null,
		        'role'=> 2
    		]);
    		\App\Models\User::create([
    			'email' => 'walker_'.$i.'@gmail.com',
		        'password' => bcrypt(123123),
		        'company_id' => $i,
		        'role'=> 3
    		]);
    		\App\Models\User::create([
    			'email' => 'client_'.$i.'@gmail.com',
		        'password' => bcrypt(123123),
		        'company_id' => null,
		        'role'=> 4
    		]);
    	}
    }
}
