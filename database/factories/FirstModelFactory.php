<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use CodencoDev\EloquentModelTester\Tests\TestModels\FirstModel;

$factory->define(FirstModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
    ];
});
