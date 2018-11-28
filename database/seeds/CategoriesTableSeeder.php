<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// DB::table('categories')->truncate();
        // factory(App\Models\Category::class, 10)->create();
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        $faker = Faker\Factory::create();

        $categories = ['SAMSUNG','NOKIA','APPLE','BLACK BERRY'];
        foreach ($categories as $key => $value) {
            Category::create([
                'name'=>$value,
                'description'=> 'This is '.$value,
                'markup'=> rand(10,100),
                'category_id'=> null,
                'UUID' => $faker->uuid,
                ]);
        }
    }
}
