<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Traits\ColumnsTestable;
use Thomasdominic\ModelsTestor\Traits\FillableTestable;
use Thomasdominic\ModelsTestor\Traits\HasManyRelationsTestable;

class FirstModelTest extends TestCase
{
    use ColumnsTestable;
    protected string $table = 'first_models';
    protected array $columns
        = [
            'id','name',
        ];

    use FillableTestable;
    protected string $toBeInFillableModel = FirstModel::class;
    protected array $toBeInFillableProperty = ['name'];


    use HasManyRelationsTestable;
    protected array $hasManyRelations
        = [
            [
                'model_class'          => FirstModel::class,
                'relation_class'       => SecondModel::class,
                'relation_name'        => 'second_models',
                'relation_foreign_key' => 'first_model_id',
            ],
        ];

}