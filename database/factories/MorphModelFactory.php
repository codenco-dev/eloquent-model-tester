<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Thomasdominic\ModelsTestor\Tests\TestModels\MorphModel;

$factory->define(MorphModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'morph_modelable_type' => null,
        'morph_modelable_id' => null,
    ];
});