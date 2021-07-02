<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\SecondModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecondModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SecondModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'first_model_id' => null,
        ];
    }
}
