<?php


namespace Thomasdominic\ModelsTestor\Tests;


use Thomasdominic\ModelsTestor\ModelsTestor;
use Thomasdominic\ModelsTestor\Tests\TestModels\FirstModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\NotAModel;
use Thomasdominic\ModelsTestor\Tests\TestModels\SecondModel;

class UnitModelTestorTest extends TestCase
{
    /**
     * @test
     */
    public function it_doesnt_run_without_model_class()
    {
        $this->assertFalse( (new ModelsTestor(NotAModel::class) )->isModelClass());
        $this->assertTrue((new ModelsTestor(FirstModel::class) )->isModelClass());
    }

    /**
     * @test
     */
    public function it_doesnt_run_without_existing_table_name()
    {
        $this->assertFalse((new ModelsTestor(null,'not_exists'))->isExistingTable());
        $this->assertTrue((new ModelsTestor(null,'first_models'))->isExistingTable());
    }

    /**
     * @test
     */
    public function it_should_have_belongs_to_relations_name()
    {
        $instance = new ModelsTestor(FirstModel::class);
        $relation = $instance->getBelongsToRelationName(SecondModel::class);
        $this->assertEquals("second_model",$relation);
    }

    /**
     * @test
     */
    public function it_should_have_has_many_relations_name()
    {
        $instance = new ModelsTestor(FirstModel::class);
        $relation = $instance->getHasManyRelationName(SecondModel::class);
        $this->assertEquals("second_models",$relation);
    }

    /**
     * @test
     */
    public function it_should_have_many_to_many_relations_name()
    {
        $instance = new ModelsTestor(FirstModel::class);
        $relation = $instance->getManyToManyRelationName(SecondModel::class);


        $this->assertEquals("second_models",$relation);
    }
}