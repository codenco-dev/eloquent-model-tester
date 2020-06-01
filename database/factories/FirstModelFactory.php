<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use CodencoDev\EloquentModelTester\Tests\TestModels\FirstModel;
use Faker\Generator as Faker;

$factory->define(FirstModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
