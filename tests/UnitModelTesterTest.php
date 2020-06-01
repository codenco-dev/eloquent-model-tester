<?php

namespace CodencoDev\EloquentModelTester\Tests;

use CodencoDev\EloquentModelTester\ModelTesterFacade as ModelTester;
use CodencoDev\EloquentModelTester\Tests\TestModels\FirstModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\NotAModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\SecondModel;

class UnitModelTesterTest extends TestCase
{
    /**
     * @test
     */
    public function it_doesnt_run_without_model_class()
    {
        $this->assertFalse((ModelTester::create(NotAModel::class) )->isModelClass());
        $this->assertTrue((ModelTester::create(FirstModel::class) )->isModelClass());
    }

    /**
     * @test
     */
    public function it_doesnt_run_without_existing_table_name()
    {
        $this->assertFalse((ModelTester::create(null, 'not_exists'))->isExistingTable());
        $this->assertTrue((ModelTester::create(null, 'first_models'))->isExistingTable());

        $this->assertTrue((ModelTester::create(FirstModel::class))->isExistingTable());
    }

    /**
     * @test
     */
    public function it_should_have_belongs_to_relations_name()
    {
        $instance = ModelTester::create(FirstModel::class);
        $relation = $instance->getBelongsToRelationName(SecondModel::class);
        $this->assertEquals('second_model', $relation);
    }

    /**
     * @test
     */
    public function it_should_have_has_many_relations_name()
    {
        $instance = ModelTester::create(FirstModel::class);
        $relation = $instance->getHasManyRelationName(SecondModel::class);
        $this->assertEquals('second_models', $relation);
    }

    /**
     * @test
     */
    public function it_should_have_many_to_many_relations_name()
    {
        $instance = ModelTester::create(FirstModel::class);
        $relation = $instance->getManyToManyRelationName(SecondModel::class);

        $this->assertEquals('second_models', $relation);
    }
}
