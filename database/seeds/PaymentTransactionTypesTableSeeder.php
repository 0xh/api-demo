<?php

use Illuminate\Database\Seeder;

class PaymentTransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_transaction_types')->truncate();
        \App\Models\PaymentTransactionType::create([
            'title' => 'Cart',
            'description' => 'Cart payment'
        ]);

        \App\Models\PaymentTransactionType::create([
            'title' => 'Subscription',
            'description' => 'Subscription payment'
        ]);
    }
}
