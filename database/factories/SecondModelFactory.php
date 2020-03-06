<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;

$factory->define(SecondModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'first_model_id' => null,
    ];
});
