<?php

namespace Thomasdominic\EloquentModelTestor\Tests;

use Thomasdominic\EloquentModelTestor\ModelTestor;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\FirstModel;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\NotAModel;
use Thomasdominic\EloquentModelTestor\Tests\TestModels\SecondModel;

class UnitModelTestorTest extends TestCase
{
    /**
     * @test
     */
    public function it_doesnt_run_without_model_class()
    {
        $this->assertFalse((new ModelTestor(NotAModel::class) )->isModelClass());
        $this->assertTrue((new ModelTestor(FirstModel::class) )->isModelClass());
    }

    /**
     * @test
     */
    public function it_doesnt_run_without_existing_table_name()
    {
        $this->assertFalse((new ModelTestor(null, 'not_exists'))->isExistingTable());
        $this->assertTrue((new ModelTestor(null, 'first_models'))->isExistingTable());

        $this->assertTrue((new ModelTestor(FirstModel::class))->isExistingTable());
    }

    /**
     * @test
     */
    public function it_should_have_belongs_to_relations_name()
    {
        $instance = new ModelTestor(FirstModel::class);
        $relation = $instance->getBelongsToRelationName(SecondModel::class);
        $this->assertEquals('second_model', $relation);
    }

    /**
     * @test
     */
    public function it_should_have_has_many_relations_name()
    {
        $instance = new ModelTestor(FirstModel::class);
        $relation = $instance->getHasManyRelationName(SecondModel::class);
        $this->assertEquals('second_models', $relation);
    }

    /**
     * @test
     */
    public function it_should_have_many_to_many_relations_name()
    {
        $instance = new ModelTestor(FirstModel::class);
        $relation = $instance->getManyToManyRelationName(SecondModel::class);

        $this->assertEquals('second_models', $relation);
    }
}
