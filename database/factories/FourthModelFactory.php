<?php

namespace Database\Factories;

use CodencoDev\EloquentModelTester\Tests\TestModels\FourthModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FourthModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FourthModel::class;

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
