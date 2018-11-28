<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        // $this->call(DeviceTableSeeder::class);
        // $this->call(UserTableSeeder::class);
        // $this->call(JumpsTableSeeder::class);
        // $this->call(RollsTableSeeder::class);
        // $this->call(SmilesTableSeeder::class);
        // $this->call(NapsTableSeeder::class);
        // $this->call(ImagesTableSeeder::class);
        // $this->call(CompaniesTableSeeder::class);
        // $this->call(SubscriptionsTableSeeder::class);
    }
}
