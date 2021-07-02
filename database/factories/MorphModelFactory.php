<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\MorphModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MorphModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MorphModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'morph_modelable_type' => null,
            'morph_modelable_id' => null,
        ];
    }
}
