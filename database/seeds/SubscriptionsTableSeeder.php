<?php

use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for($i = 0; $i < 200; $i++ ){
          App\Models\Subscription::create([
              'user_id' => rand(2,10),
              'plan_id' => rand(1,50),
              'name' => $faker->name,
              'stripe_id' => str_random(15),
              'stripe_plan' => str_random(10),
              'quantity' => rand(1, 5),
              'trial_ends_at' => $faker->dateTimeBetween('-365 days', 'now'),
              'ends_at' => $faker->dateTimeBetween('-365 days', 'now'),
              'status' => 1,
              'created_at' => $faker->dateTimeBetween('-365 days', 'now')
            ]);
        }
    }
}
