<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\FirstModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FirstModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FirstModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
