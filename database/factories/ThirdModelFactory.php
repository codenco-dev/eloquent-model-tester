<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use CodencoDev\EloquentModelTester\Tests\TestModels\ThirdModel;

$factory->define(ThirdModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
