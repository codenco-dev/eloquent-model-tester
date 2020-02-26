<?php

namespace Thomasdominic\ModelsTestor;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Thomasdominic\ModelsTestor\Traits\HasManyRelationsTestable;

trait ModelsTestorTrait
{
    public function assertHasColumns($columns, ?string $modelClass = null, ?string $table = null)
    {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }

        $this->columns = $columns;
        collect($this->columns)->each(function ($column) {
            $this->assertTrue(in_array($column,Schema::getColumnListing($this->getModelTable())));
        });

        return $this;
    }

    protected function setModelTestable(string $modelClass, ?string $table = null)
    {
        if ($table) {
            $this->setTableTestable($table);
        }
        $this->model_testable = $modelClass;

        return $this;
    }

    protected function setTableTestable(?string $table = null)
    {
        if ($table) {
            $this->table = $table;
        }

        return $this;
    }

    public function getModelTable()
    {
        if (!empty($this->table)) {
            return $this->table;
        }

        return (new $this->model_testable)->getTable();
    }

    public function assertCanFillables(array $fillable = [], string $modelClass = null, string $table = null)
    {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }
        $this->fillable = $fillable;

        $t = collect(array_flip($this->fillable))->transform(function ($item, $key) {
            return 'value_for_test';
        });

        $model = (new $this->model_testable)->fill($t->toArray());
        $this->assertEquals([], collect($t->toArray())->diffAssoc($model->toArray())->toArray());

        return $this;
    }

    public function assertHasHasManyRelations(array $hasManyRelations, string $modelClass = null, string $table = null)
    {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }


        $this->hasManyRelations = $hasManyRelations;

        foreach ($this->hasManyRelations as $relation) {
            $model = factory($this->model_testable)->create();
            $related = $model->{$relation['relation_name']}()->save(factory($relation['relation_class'])->make());
            $model->refresh();
            $this->assertTrue($model->{$relation['relation_name']}->contains($related));
            $this->assertEquals(1, $model->{$relation['relation_name']}->count());

            $this->assertInstanceOf(Collection::class, $model->{$relation['relation_name']});
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());
        }

        return $this;
    }

    public function assertHasBelongsToRelations(
        array $belongsToRelations,
        string $modelClass = null,
        string $table = null
    ) {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }

        $this->belongsToRelations = $belongsToRelations;

        foreach ($this->belongsToRelations as $relation) {

            $related = factory($relation['relation_class'])->create();

            $model = factory($this->model_testable)->create([$relation['relation_foreign_key'] => $related->id]);


            $this->assertEquals($model->{$relation['relation_name']}->id, $related->id);
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']});

            $related2 = factory($relation['relation_class'])->create();
            $model2 = factory($this->model_testable)->make();
            $model2->{$relation['relation_name']}()->associate($related2)->save();

            $this->assertEquals($model2->{$relation['relation_foreign_key']}, $related2->id);
            $this->assertInstanceOf($relation['relation_class'], $model2->{$relation['relation_name']});
        }

        return $this;
    }

    public function assertHasManyToManyRelations(
        array $manyToManyRelations,
        string $modelClass = null,
        string $table = null
    ) {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }

        $this->manyToManyRelations = $manyToManyRelations;
        foreach ($this->manyToManyRelations as $relation) {

            $model = factory($this->model_testable)->create();
            $related = factory($relation['relation_class'])->create();
            $model->{$relation['relation_name']}()->attach($related);

            $this->assertTrue($model->{$relation['relation_name']}->contains($related));
            $this->assertEquals($related->id, $model->{$relation['relation_name']}->first()->id);
            $this->assertEquals(1, $model->{$relation['relation_name']}->count());
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());

        }

        return $this;
    }

    public function assertHasHasManyMorphRelations(
        array $hasManyMorphRelations,
        string $modelClass = null,
        string $table = null
    ) {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }
        $this->hasManyMorphRelations = $hasManyMorphRelations;
        foreach ($this->hasManyMorphRelations as $relation) {

            $morphable = factory($this->model_testable)->create();
            $morphable->{$relation['morph_relation']}()->save(factory($relation['morph_model_class'])->make());

            $morphable->refresh();
            $this->assertInstanceOf($relation['morph_model_class'], $morphable->{$relation['morph_relation']}->first());
        }
    }

    public function assertHasBelongsToMorphRelations(
        array $belongsToMorphRelations,
        string $modelClass = null,
        string $table = null
    ) {
        if ($modelClass) {
            $this->setModelTestable($modelClass, $table);
        }
        if ($table) {
            $this->setTableTestable($table);
        }
        $this->belongsToMorphRelations = $belongsToMorphRelations;

        foreach ($this->belongsToMorphRelations as $relation) {
            $morphable = factory($relation['morphable_model_class'])->create();
            $morph = factory($this->model_testable)->create([
                $relation['morph_relation'].'_id' => $morphable->id,
                $relation['morph_relation'].'_type' => $relation['morphable_model_class'],
            ]);

            $morph->refresh();
            $this->assertInstanceOf($relation['morphable_model_class'], $morph->{$relation['morph_relation']});
        }

    }

}
