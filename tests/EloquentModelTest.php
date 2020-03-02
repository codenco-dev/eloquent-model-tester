<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\ModelsTestorTrait;
use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\MorphModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\NotAModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\ThirdModel;

class EloquentModelTest extends TestCase
{

    use ModelsTestorTrait;

    public function test_it_doesnt_run_without_model_class()
    {
        $this->assertFalse($this->modelTestable(NotAModel::class)->isModelClass());
        $this->assertTrue($this->modelTestable(FirstModel::class)->isModelClass());

    }

    public function test_it_doesnt_run_without_existing_table_name()
    {
        $this->assertFalse($this->tableTestable("not_exists")->isExistingTable());
        $this->assertTrue($this->tableTestable("first_models")->isExistingTable());

    }

    public function test_have_first_model_model()
    {
        $this->modelTestable(FirstModel::class)
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
        $this->modelTestable(SecondModel::class)
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
        $this->modelTestable(ThirdModel::class)
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
        $this->modelTestable(MorphModel::class)
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

    public function test_have_table_without_model()
    {
        $this->tableTestable('second_model_third_model')
            ->assertHasColumns(['second_model_id','third_model_id']);
    }
}