<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\ThirdModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThirdModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThirdModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
