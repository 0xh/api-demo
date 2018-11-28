<?php

use Illuminate\Database\Seeder;

class RollsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Roll::class, 50)->create();
    }
}
