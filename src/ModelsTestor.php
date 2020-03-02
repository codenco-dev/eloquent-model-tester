<?php

namespace Thomasdominic\ModelsTestor;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class ModelsTestor extends TestCase
{
    /**
     * @var string
     */
    private ?string $model_testable;
    /**
     * @var string|null
     */
    private ?string $table;

    public function __construct(?string $model_testable, ?string $table = null)
    {
        parent::__construct();
        $this->model_testable = $model_testable;
        $this->table = $table;
    }

    public function getModel()
    {
        throw_if(is_null($this->model_testable) || !$this->isModelClass($this->model_testable),
            new \Exception("You have to use a Eloquent Model"));

        return $this->model_testable;
    }

    public function getTable()
    {
        throw_if(!$this->isExistingTable(),
            new \Exception("You have to use an existing table"));

        return $this->getModelTable();
    }

    public function getModelTable()
    {
        if (!empty($this->table)) {
            return $this->table;
        }

        $modelClass = $this->getModel();

        return (new $modelClass)->getTable();
    }

    public function isModelClass(?string $modelClass = null)
    {
        if (!is_null($modelClass)) {
            return ((new $modelClass) instanceof Model);
        } else {
            return ((new $this->model_testable) instanceof Model);
        }

    }

    public function isExistingTable(?string $tableName = null)
    {
        if (!is_null($tableName)) {
            return Schema::hasTable($tableName);
        } else {
            return Schema::hasTable($this->getModelTable());
        }
    }


    public function assertHasColumns(array $columns)
    {
        collect($columns)->each(function ($column) {
            $this->assertTrue(in_array($column, Schema::getColumnListing($this->getTable())));
        });

        return $this;
    }


    public function assertCanFillables(array $fillable = [])
    {
        $modelClass = $this->getModel();
        $this->assertEquals([], collect($fillable)->diff((new $modelClass)->getFillable())->toArray());
        return $this;
    }

    public function assertHasHasManyRelations(array $hasManyRelations)
    {
        foreach ($hasManyRelations as $relation) {
            $model = factory($this->getModel())->create();
            $related = $model->{$relation['relation_name']}()->save(factory($relation['relation_class'])->make());
            $model->refresh();
            $this->assertTrue($model->{$relation['relation_name']}->contains($related));
            $this->assertEquals(1, $model->{$relation['relation_name']}->count());

            $this->assertInstanceOf(Collection::class, $model->{$relation['relation_name']});
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());
        }

        return $this;
    }

    public function assertHasBelongsToRelations(array $belongsToRelations)
    {


        foreach ($belongsToRelations as $relation) {

            $related = factory($relation['relation_class'])->create();

            $model = factory($this->getModel())->create([$relation['relation_foreign_key'] => $related->id]);


            $this->assertEquals($model->{$relation['relation_name']}->id, $related->id);
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']});

            $related2 = factory($relation['relation_class'])->create();
            $model2 = factory($this->getModel())->make();
            $model2->{$relation['relation_name']}()->associate($related2)->save();

            $this->assertEquals($model2->{$relation['relation_foreign_key']}, $related2->id);
            $this->assertInstanceOf($relation['relation_class'], $model2->{$relation['relation_name']});
        }

        return $this;
    }

    public function assertHasManyToManyRelations(array $manyToManyRelations)
    {

        foreach ($manyToManyRelations as $relation) {

            $model = factory($this->getModel())->create();
            $related = factory($relation['relation_class'])->create();
            $model->{$relation['relation_name']}()->attach($related);

            $this->assertTrue($model->{$relation['relation_name']}->contains($related));
            $this->assertEquals($related->id, $model->{$relation['relation_name']}->first()->id);
            $this->assertEquals(1, $model->{$relation['relation_name']}->count());
            $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());

        }

        return $this;
    }

    public function assertHasHasManyMorphRelations(array $hasManyMorphRelations)
    {

        foreach ($hasManyMorphRelations as $relation) {

            $morphable = factory($this->getModel())->create();
            $morphable->{$relation['morph_relation']}()->save(factory($relation['morph_model_class'])->make());

            $morphable->refresh();
            $this->assertInstanceOf($relation['morph_model_class'], $morphable->{$relation['morph_relation']}->first());
        }
    }

    public function assertHasBelongsToMorphRelations(array $belongsToMorphRelations)
    {


        foreach ($belongsToMorphRelations as $relation) {
            $morphable = factory($relation['morphable_model_class'])->create();
            $morph = factory($this->getModel())->create([
                $relation['morph_relation'].'_id'   => $morphable->id,
                $relation['morph_relation'].'_type' => $relation['morphable_model_class'],
            ]);

            $morph->refresh();
            $this->assertInstanceOf($relation['morphable_model_class'], $morph->{$relation['morph_relation']});
        }

    }
}
