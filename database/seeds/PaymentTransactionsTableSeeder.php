<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();
        for($i=0; $i < 200; $i++){
        	App\Models\PaymentTransaction::create([
                'order_id' => rand(1,200),
                'transaction_type' => 1,
                'transaction_method' => 'Cart',
        		'amount' => rand(10,1000),
        		'created_at' => $faker->dateTimeBetween('-365 days', 'now'),
        		'last4' => '4242',
        		'Brand' => 'Visa',
        		'exp_month' => rand(1,12),
        		'exp_year' => rand(2018,2020),
        		'token' => '',
        		'status' => 1
        	]);
        }
    }
}
