<?php

namespace CodencoDev\EloquentModelTester\Tests;

use CodencoDev\EloquentModelTester\HasModelTester;
use CodencoDev\EloquentModelTester\Tests\TestModels\FifthModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\FirstModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\FourthModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\MorphModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\SecondModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\SixthModel;
use CodencoDev\EloquentModelTester\Tests\TestModels\ThirdModel;
use PHPUnit\Framework\ExpectationFailedException;

class EloquentModelTest extends TestCase
{
    use HasModelTester;

    /**
     * @test
     */
    public function it_have_first_model_model()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasColumns('id', 'name')
            ->assertHasOnlyColumns('id', 'name')
            ->assertCanFillables(['name'])
            ->assertHasOnlyColumnsInFillable(['id', 'name'])
            ->assertHasNoGuardedAndFillableFields()
            ->assertCanOnlyFill(['id','name'])
            ->assertHasHasManyRelation(SecondModel::class)
            ->assertHasHasManyThroughRelation(FifthModel::class, SecondModel::class);
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
        $column = ['id', 'name', 'first_model_id'];
        $this->modelTestable(SecondModel::class)
            ->assertHasColumns($column)
            ->assertCanFillables($column)
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model')
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model', 'first_model_id')
            ->assertHasManyToManyRelation(ThirdModel::class, 'third_models')
            ->assertHasManyToManyRelation(FourthModel::class, 'fourth_models', ['pivot_field' => 'test'])
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
    public function it_have_fifth_model_model()
    {
        $this->modelTestable(FifthModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertCanFillables(['name'])
            ->assertHasColumnsInGuarded('isAdmin')
            ->assertHasOnlyColumnsInGuarded('isAdmin');
    }

    /**
     * @test
     */
    public function it_tests_for_the_timestamps()
    {
        $this->modelTestable(SixthModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertHasTimestampsColumns()
            ->assertHasSoftDeleteTimestampColumns();
    }

    /**
     * @test
     */
    public function it_fails_when_the_fillable_field_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertCanOnlyFill(['id', 'name','second_model_id', 'isAdmin']);
    }

    /**
     * @test
     */
    public function it_fails_when_the_table_columns_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasOnlyColumns(['id', 'name','second_model_id', 'isAdmin', 'missing']);
    }

    /**
     * @test
     */
    public function it_fails_when_the_guarded_field_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasOnlyColumnsInGuarded(['id', 'isAdmin']);
    }

    /**
     * @test
     */
    public function it_fails_when_the_guarded_field_does_not_include_the_expected_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasColumnsInGuarded(['id']);
    }

    /**
     * @test
     */
    public function it_fails_when_the_fillable_field_does_not_include_the_expected_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasColumnsInFillable(['isAdmin']);
    }

    /**
     * @test
     */
    public function it_fails_when_the_guarded_and_fillable_arrays_contain_the_same_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasNoGuardedAndFillableFields();
    }

    /**
     * @test
     */
    public function it_fails_when_the_guarded_and_fillable_arrays_contain_the_same_value_when_asserting_only_fillable()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertCanOnlyFill('id','name','isAdmin');
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

    /**
     * @test
     */
    public function it_have_scope_in_first_model()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasScope('theScope1')
            ->assertHasScope('theScope2', 'param1')
            ->assertHasScope('theScope3')
            ->assertHasScope('theScope4', 'param1', 'param2');
    }
}
