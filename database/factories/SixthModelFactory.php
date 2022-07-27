<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\SixthModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SixthModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SixthModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'first_model_id' => null,
            'isAdmin' => $this->faker->boolean,
        ];
    }
}
