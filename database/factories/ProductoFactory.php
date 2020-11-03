<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Modelos\Producto::class, function (Faker $faker) {
    return [
        'nombre'=> $faker->text(10),
        'precio'=> rand(18,50),
    ];
});
