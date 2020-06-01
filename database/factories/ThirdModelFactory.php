<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use CodencoDev\EloquentModelTester\Tests\TestModels\ThirdModel;
use Faker\Generator as Faker;

$factory->define(ThirdModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
