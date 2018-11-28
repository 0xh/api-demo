<?php

use Illuminate\Database\Seeder;
use App\Models\Device;
class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('devices')->truncate();
        // factory(App\Models\Device::class, 10)->create();

        Schema::disableForeignKeyConstraints();
        Device::truncate();
        $faker = Faker\Factory::create();

        
    }
}
