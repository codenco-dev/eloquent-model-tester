<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\MorphModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\ThirdModel;
use Thomasdominic\ModelsTestor\Traits\BelongToRelationsTestable;
use Thomasdominic\ModelsTestor\Traits\ColumnsTestable;
use Thomasdominic\ModelsTestor\Traits\FillableTestable;
use Thomasdominic\ModelsTestor\Traits\HasManyMorphRelationsTestable;
use Thomasdominic\ModelsTestor\Traits\ManyToManyRelationsTestable;

class SecondModelTest extends TestCase
{
    use ColumnsTestable;
    protected string $table = 'second_models';
    protected array $columns
        = [
            'id','name','first_model_id',
        ];

    use FillableTestable;
    protected string $toBeInFillableModel = SecondModel::class;
    protected array $toBeInFillableProperty = ['name','first_model_id'];

    use BelongToRelationsTestable;
    protected array $belongToRelations
        = [
            [
                'model_class'          => SecondModel::class,
                'relation_class'       => FirstModel::class,
                'relation_name'        => 'first_model',
                'relation_foreign_key' => 'first_model_id',
            ]
        ];

    use ManyToManyRelationsTestable;
    protected array $manyToManyRelations=[
        [
            'model_class'    => SecondModel::class,
            'relation_class' => ThirdModel::class,
            'relation_name'  => 'third_models',
        ],
    ];

    use HasManyMorphRelationsTestable;
    protected array $hasManyMorphRelations
        = [
            [
                'morph_model_class'     => MorphModel::class,
                'morphable_model_class' => SecondModel::class,
                'morph_relation'        => 'morph_models',
            ]
        ];

}