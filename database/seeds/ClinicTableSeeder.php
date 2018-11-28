<?php

use Illuminate\Database\Seeder;

class ClinicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('clinics')->truncate();
        factory(App\Models\Clinic::class, 20)->create();
    }
}
