<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use CodencoDev\EloquentModelTester\Tests\TestModels\SecondModel;
use Faker\Generator as Faker;

$factory->define(SecondModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'first_model_id' => null,
    ];
});
