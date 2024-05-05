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

    
    public function test_it_have_first_model_model()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasColumns('id', 'name')
            ->assertHasOnlyColumns('id', 'name')
            ->assertHasColumnsInFillable(['name'])
            ->assertHasOnlyColumnsInFillable(['id', 'name'])
            ->assertHasNoGuardedAndFillableFields()
            ->assertCanOnlyFill(['id', 'name'])
            ->assertHasHasManyRelation(SecondModel::class)
            ->assertHasHasManyThroughRelation(FifthModel::class, SecondModel::class)
            ->assertHasHasOneRelation(SixthModel::class);
    }

    
    public function test_it_have_first_model_model_without_relation_parameter()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasHasManyRelation(SecondModel::class);
    }

    
    public function test_it_have_second_model_model()
    {
        $column = ['id', 'name', 'first_model_id'];
        $this->modelTestable(SecondModel::class)
            ->assertHasColumns($column)
            ->assertHasColumnsInFillable($column)
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model')
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model', 'first_model_id')
            ->assertHasManyToManyRelation(ThirdModel::class, 'third_models')
            ->assertHasManyToManyRelation(FourthModel::class, 'fourth_models', ['pivot_field' => 'test'])
            ->assertHasHasManyMorphRelation(MorphModel::class, 'morph_models');
    }

    
    public function test_it_have_second_model_model_without_relation_parameter()
    {
        $this->modelTestable(SecondModel::class)
            ->assertHasBelongsToRelation(FirstModel::class)
            ->assertHasManyToManyRelation(ThirdModel::class);
    }

    
    public function test_it_have_third_model_model()
    {
        $this->modelTestable(ThirdModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertHasColumnsInFillable(['name'])
            ->assertHasManyToManyRelation(SecondModel::class, 'second_models');
    }

    
    public function test_it_have_fifth_model_model()
    {
        $this->modelTestable(FifthModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertHasColumnsInFillable(['name'])
            ->assertCanOnlyFill('name', 'id', 'second_model_id')
            ->assertHasColumnsInGuarded('is_admin')
            ->assertHasOnlyColumnsInGuarded('is_admin');
    }

    
    public function test_it_tests_for_the_timestamps()
    {
        $this->modelTestable(SixthModel::class)
            ->assertHasColumns(['id', 'name'])
            ->assertHasTimestampsColumns()
            ->assertHasSoftDeleteTimestampColumns();
    }

    
    public function test_it_fails_when_the_fillable_field_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertCanOnlyFill(['id', 'name', 'second_model_id', 'is_admin']);
    }

    
    public function test_it_fails_when_the_table_columns_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasOnlyColumns(['id', 'name', 'second_model_id', 'is_admin', 'missing']);
    }

    
    public function test_it_fails_when_the_guarded_field_is_not_exactly_as_expected()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasOnlyColumnsInGuarded(['id', 'is_admin']);
    }

    
    public function test_it_fails_when_the_guarded_field_does_not_include_the_expected_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasColumnsInGuarded(['id']);
    }

    
    public function test_it_fails_when_the_fillable_field_does_not_include_the_expected_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(FifthModel::class)
            ->assertHasColumnsInFillable(['is_admin']);
    }

    
    public function test_it_fails_when_the_guarded_and_fillable_arrays_contain_the_same_value()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasNoGuardedAndFillableFields();
    }

    
    public function test_it_fails_when_the_guarded_and_fillable_arrays_contain_the_same_value_when_asserting_only_fillable()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertCanOnlyFill('id', 'name', 'is_admin');
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_one_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasHasOneRelation(FifthModel::class);
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_morph_one_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasMorphOneRelation(FirstModel::class, 'morph_models');
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_many_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasHasManyRelation(FirstModel::class);
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_many_through_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasHasManyThroughRelation(FirstModel::class, SecondModel::class);
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_belongs_to_relation()
    {
        FirstModel::factory()->create();

        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasBelongsToRelation(FifthModel::class, null, 'first_model_id');
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_many_to_many_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasManyToManyRelation(FirstModel::class);
    }

    
    public function test_it_fails_when_the_model_does_not_have_the_has_many_morph_relation()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->modelTestable(SixthModel::class)
            ->assertHasHasManyMorphRelation(FirstModel::class, 'morph_models');
    }

    
    public function test_it_have_morph_model_model()
    {
        $this->modelTestable(MorphModel::class)
            ->assertHasColumns(['id', 'name', 'morph_modelable_type', 'morph_modelable_id'])
            ->assertHasColumnsInFillable(['name', 'morph_modelable_type', 'morph_modelable_id'])
            ->assertHasBelongsToMorphRelation(SecondModel::class, 'morph_modelable')
            ->assertHasBelongsToMorphRelation(SixthModel::class, 'morph_modelable');
    }

    
    public function test_it_have_sixth_model_model()
    {
        $column = ['id', 'name', 'first_model_id'];
        $this->modelTestable(SixthModel::class)
            ->assertHasColumns($column)
            ->assertHasColumnsInFillable($column)
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model')
            ->assertHasBelongsToRelation(FirstModel::class, 'first_model', 'first_model_id')
            ->assertHasMorphOneRelation(MorphModel::class, 'morphed');
    }

    
    public function test_it_have_table_without_model()
    {
        $this->tableTestable('second_model_third_model')
            ->assertHasColumns(['second_model_id', 'third_model_id']);
    }

    
    public function test_it_have_scope_in_first_model()
    {
        $this->modelTestable(FirstModel::class)
            ->assertHasScope('theScope1')
            ->assertHasScope('theScope2', 'param1')
            ->assertHasScope('theScope3')
            ->assertHasScope('theScope4', 'param1', 'param2');
    }
}
