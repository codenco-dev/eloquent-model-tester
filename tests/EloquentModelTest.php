<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\ModelsTestor;
use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\MorphModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\ThirdModel;

class EloquentModelTest extends TestCase
{
//    use ColumnsTestable;
//    protected string $table = 'first_models';
//    protected array $columns
//        = [
//            'id', 'name',
//        ];
//
//    use FillableTestable;
//    protected string $toBeInFillableModel = FirstModel::class;
//    protected array $toBeInFillableProperty = ['name'];
//
//
//    use HasManyRelationsTestable;
//    protected array $hasManyRelations
//        = [
//            [
//                'model_class'          => FirstModel::class,
//                'relation_class'       => SecondModel::class,
//                'relation_name'        => 'second_models',
//                'relation_foreign_key' => 'first_model_id',
//            ],
//        ];

    use ModelsTestor;

    public function test_have_first_model_model()
    {
        $this->setModelTestable(FirstModel::class)
            ->assertHasColumns(['id', 'name',])
            ->assertCanFillables(['name'])
            ->assertHasHasManyRelations([
                [
                    'relation_class' => SecondModel::class,
                    'relation_name'  => 'second_models',
                ],
            ]);
    }

    public function test_have_second_model_model()
    {
        $this->setModelTestable(SecondModel::class)
            ->assertHasColumns(['id', 'name', 'first_model_id',])
            ->assertCanFillables(['name', 'first_model_id'])
            ->assertHasBelongsToRelations([
                [
                    'relation_class'       => FirstModel::class,
                    'relation_name'        => 'first_model',
                    'relation_foreign_key' => 'first_model_id',
                ],
            ])
            ->assertHasManyToManyRelations([
                [
                    'relation_class' => ThirdModel::class,
                    'relation_name'  => 'third_models',
                ],
            ])
            ->assertHasHasManyMorphRelations([
                    [
                        'morph_model_class'     => MorphModel::class,
                        'morph_relation'        => 'morph_models',
                    ],
                ]
            );
    }

    public function test_have_third_model_model()
    {
        $this->setModelTestable(ThirdModel::class)
            ->assertHasColumns([
                'id', 'name',
            ])
            ->assertCanFillables(['name'])
            ->assertHasManyToManyRelations([
                [
                    'relation_class' => SecondModel::class,
                    'relation_name'  => 'second_models',
                ],
            ]);
    }

    public function test_have_morph_model_model()
    {
        $this->setModelTestable(MorphModel::class)
            ->assertHasColumns(['id','name','morph_modelable_type','morph_modelable_id',])
            ->assertCanFillables(['name','morph_modelable_type','morph_modelable_id'])
            ->assertHasBelongsToMorphRelations([
                    [
                        'morphable_model_class' => SecondModel::class,
                        'morph_relation'        => 'morph_modelable',
                    ],
                ]
            );
    }
}