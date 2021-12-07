<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\FifthModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FifthModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FifthModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'second_model_id' => null,
        ];
    }
}
