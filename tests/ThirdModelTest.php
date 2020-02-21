<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\ThirdModel;
use Thomasdominic\ModelsTestor\Traits\ColumnsTestable;
use Thomasdominic\ModelsTestor\Traits\FillableTestable;
use Thomasdominic\ModelsTestor\Traits\ManyToManyRelationsTestable;

class ThirdModelTest extends TestCase
{
    use ColumnsTestable;
    protected string $table = 'third_models';
    protected array $columns
        = [
            'id','name',
        ];

    use FillableTestable;
    protected string $toBeInFillableModel = ThirdModel::class;
    protected array $toBeInFillableProperty = ['name'];

    use ManyToManyRelationsTestable;
    protected array $manyToManyRelations=[
        [
            'model_class'    => ThirdModel::class,
            'relation_class' => SecondModel::class,
            'relation_name'  => 'second_models',
        ],
    ];
}