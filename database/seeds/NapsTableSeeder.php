<?php

use Illuminate\Database\Seeder;

class NapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Nap::class, 50)->create();
    }
}
