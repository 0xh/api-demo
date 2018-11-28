<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Models\Product::class, 50)->create();
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        $faker = Faker\Factory::create();
        $categories = \App\Models\Category::all();

        foreach ($categories as $key_cat => $value_cat) {

        	$products = ['product-001','product-002','product-003','product-004'];

        	foreach ($products as $key_product => $value_product) {
        		$product = Product::create([
        			'name' => $value_cat->name.'-'.$value_product,
			        'price'=> 100,
			        'description' => 'This is '.$value_cat->name.'-'.$value_product,
			        'category_id'=>$value_cat->id,
			        'sku' =>$value_cat->name.'-'.$value_product,
			        'UUID'=>$faker->uuid,
        		]);
        	}
        }


    }
}
