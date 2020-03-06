<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;

$factory->define(FirstModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
