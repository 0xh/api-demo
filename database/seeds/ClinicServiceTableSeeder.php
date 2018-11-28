<?php

use Illuminate\Database\Seeder;

class ClinicServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Clinic_service::class, 15)->create();
    }
}
