<?php

namespace Thomasdominic\EloquentModelTestor\Tests;

use Thomasdominic\EloquentModelTestor\HasModelTestor;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\FirstModel;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\MorphModel;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\SecondModel;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\ThirdModel;

class EloquentModelTest extends TestCase
{
    use HasModelTestor;

    /**
     * @test
     */
    public function it_have_first_model_model()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertCanFillables(['name'])
            ->assertHasHasManyRelation(SecondModel::class, 'second_models');
    }

    /**
     * @test
     */
    public function it_have_first_model_model_without_relation_parameter()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasHasManyRelation(SecondModel::class);
    }

    /**
     * @test
     */
    public function it_have_second_model_model()
    {
        $this->modelTestable(SecondModel::class)
            ->assertHasColumns(['id', 'name', 'first_model_id'])
            ->assertCanFillables(['name', 'first_model_id'])
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model')
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model', 'first_model_id')
            ->assertHasManyToManyRelation(ThirdModel::class, 'third_models')
            ->assertHasHasManyMorphRelation(MorphModel::class, 'morph_models');
    }

    /**
     * @test
     */
    public function it_have_second_model_model_without_relation_parameter()
    {
        $this->modelTestable(SecondModel::class)
            ->assertHasBelongsToRelation(FirstModel::class)
            ->assertHasManyToManyRelation(ThirdModel::class);
    }

    /**
     * @test
     */
    public function it_have_third_model_model()
    {
        $this->modelTestable(ThirdModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertCanFillables(['name'])
            ->assertHasManyToManyRelation(SecondModel::class, 'second_models');
    }

    /**
     * @test
     */
    public function it_have_morph_model_model()
    {
        $this->modelTestable(MorphModel::class)
            ->assertHasColumns(['id', 'name', 'morph_modelable_type', 'morph_modelable_id'])
            ->assertCanFillables(['name', 'morph_modelable_type', 'morph_modelable_id'])
            ->assertHasBelongsToMorphRelation(SecondModel::class, 'morph_modelable');
    }

    /**
     * @test
     */
    public function it_have_table_without_model()
    {
        $this->tableTestable('second_model_third_model')
            ->assertHasColumns(['second_model_id', 'third_model_id']);
    }
}
