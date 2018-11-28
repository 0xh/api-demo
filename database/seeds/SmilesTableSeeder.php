<?php

use Illuminate\Database\Seeder;

class SmilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Smile::class, 50)->create();
    }
}
