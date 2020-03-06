<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\ThirdModel;

$factory->define(ThirdModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
