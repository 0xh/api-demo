<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Jump::class, function (Faker\Generator $faker) {

    return [
        'amount' => rand(5,20),
        'device_id' => 1,
        'pet_id' => 5,
        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Models\Nap::class, function (Faker\Generator $faker) {

    return [
        'amount' => rand(0,10),
        'device_id' => 1,
        'pet_id' => 5,
        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Models\Roll::class, function (Faker\Generator $faker) {

    return [
        'amount' => rand(0,100),
        'device_id' => 1,
        'pet_id' => 5,
        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Models\Smile::class, function (Faker\Generator $faker) {

    return [
        'amount' => rand(0,100),
        'device_id' => 1,
        'pet_id' => 5,
        'created_at'=> $faker->dateTimeBetween($startDate = '-6 months', $endDate = '+ 6 months', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Models\Ratting::class, function (Faker\Generator $faker) {

    return [
        'company_id'=>rand(1,20),
        'score'=> rand(1,5),
        'comment' =>$faker->text(70),
        'user_id' =>rand(1,10),
    ];
});

$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'city_id' => 1812,
        'country_id' => 37,
        'user_id' => 2,
        'postal' => 'M3C',
        'description' => $faker->text(50)
    ];
});

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'description' => $faker->text(50),
        'markup' => rand(10,100),
        'category_id'=> rand(1,10)
    ];
});
$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'description' => $faker->text(50),
        'price' => rand(50, 1000),
        'category_id'=>rand(1,10),
        'sku'=> 'P'.(new DateTime())->getTimestamp().'-'.rand(),
    ];
});
$factory->define(App\Models\Image::class, function (Faker\Generator $faker) {

    return [
        'url' => '1493277398.png',
        'product_id' => rand(4,50),
    ];
});

$factory->define(App\Models\Rating::class, function (Faker\Generator $faker) {

    return [
        'company_id' => 1,
        'score' => rand(1,5),
        'comment' => $faker->text(50),
        'user_id' => rand(1,2)
    ];
});

$factory->define(App\Models\Device::class, function (Faker\Generator $faker) {

    return [
        'imei' => str_random(15),
        'name' => $faker->text(10),
        'user_id' => rand(1,2),
        'battery' => rand(10,100),
        'phone' => array_rand(['01674725821', '0982312321', '0983217321']),
        'mode' => rand(0,1),
        'company_id' => rand(1,2),
        'product_id' => rand(1,10)
    ];
});

$factory->define(App\Models\Pet::class, function (Faker\Generator $faker) {

    return [
        'name' => 'teppy-'.str_random(10),
        'description' => $faker->text(50),
        'device_id' => null,
        'breed_id' => rand(1,2),
        'animal_id' => 1,
        'user_id' => rand(1,31)
    ];
});

$factory->define(App\Models\Location::class, function (Faker\Generator $faker) {

    return [
        'device_id' => 2,
        'user_id' => 2,
        'lat' => 16.026868,
        'long' => 108.222026,
    ];
});
$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(20),
        'address' => $faker->address,
        'city_id' => rand(1,10),
        'country_id' => rand(1,10),
        'user_id' => rand(1,3),
        'postal' => '32423355532',
        'description' => $faker->text(50),

    ];
});

$factory->define(App\Models\Clinic::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(20),
        'description' => $faker->text(50),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'lat' => $faker->latitude,
        'long' => $faker->longitude
    ];
});

$factory->define(App\Models\Clinic_service::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text(20)
    ];
});

$factory->define(App\Models\Animal::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'description' => $faker->text(50)
    ];
});

$factory->define(App\Models\Breed::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'description' => $faker->text(50),
        'animal_id' => rand(1, 5)
    ];
});