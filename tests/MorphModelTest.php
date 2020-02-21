<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\Tests\TestModels\MorphModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Traits\BelongToMorphRelationsTestable;
use Thomasdominic\ModelsTestor\Traits\ColumnsTestable;
use Thomasdominic\ModelsTestor\Traits\FillableTestable;

class MorphModelTest extends TestCase
{
    use ColumnsTestable;
    protected string $table = 'morph_models';
    protected array $columns
        = [
            'id','name','morph_modelable_type','morph_modelable_id',
        ];

    use FillableTestable;
    protected string $toBeInFillableModel = MorphModel::class;
    protected array $toBeInFillableProperty = ['name','morph_modelable_type','morph_modelable_id'];

    use BelongToMorphRelationsTestable;

    protected array $belongToMorphRelations
        = [
            [
                'morph_model_class'     => MorphModel::class,
                'morphable_model_class' => SecondModel::class,
                'morph_relation'        => 'morph_modelable',
            ],
        ];
}