<?php

namespace Thomasdominic\ModelsTestor;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class ModelsTestor extends TestCase
{
    /**
     * @var string
     */
    private string $model_testable;
    /**
     * @var string|null
     */
    private ?string $table;

    public function __construct(string $model_testable, ?string $table = null)
    {
        $this->model_testable = $model_testable;
        $this->table = $table;
    }

    public function __call($name, $arguments)
    {
            throw_if(($name == "assertHasColumns" && is_null($this->getModelTable())),
                (new \Exception("You have to declare the table's name")));

            throw_if(is_null($this->model_testable),(new \Exception("You have to declare the model's name")));
    }

    public function assertHasColumns(array $columns)
    {
        $this->columns = $columns;
        collect($this->columns)->each(function ($column) {
            $this->assertTrue(in_array($column, Schema::getColumnListing($this->getModelTable())));
        });

        return $this;
    }


    public function getModelTable()
    {
        if (!empty($this->table)) {
            return $this->table;
        }

        return (new $this->model_testable)->getTable();
    }

    public function assertCanFillables(array $fillable = [])
    {


        $t = collect(array_flip($fillable))->transform(function ($item, $key) {
            return 'value_for_test';
        });

        $model = (new $this->model_testable)->fill($t->toArray());
        $this->assertEquals([], collect($t->toArray())->diffAssoc($model->toArray())->toArray());

        return $this;
    }

    public function assertHasHasManyRelations(array $hasManyRelations)
    {

        foreach ($hasManyRelations as $relation) {
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

    public function assertHasBelongsToRelations(array $belongsToRelations) {



        foreach ($belongsToRelations as $relation) {

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

    public function assertHasManyToManyRelations(array $manyToManyRelations) {

        foreach ($manyToManyRelations as $relation) {

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

    public function assertHasHasManyMorphRelations(array $hasManyMorphRelations) {

        foreach ($hasManyMorphRelations as $relation) {

            $morphable = factory($this->model_testable)->create();
            $morphable->{$relation['morph_relation']}()->save(factory($relation['morph_model_class'])->make());

            $morphable->refresh();
            $this->assertInstanceOf($relation['morph_model_class'], $morphable->{$relation['morph_relation']}->first());
        }
    }

    public function assertHasBelongsToMorphRelations(array $belongsToMorphRelations) {


        foreach ($belongsToMorphRelations as $relation) {
            $morphable = factory($relation['morphable_model_class'])->create();
            $morph = factory($this->model_testable)->create([
                $relation['morph_relation'].'_id'   => $morphable->id,
                $relation['morph_relation'].'_type' => $relation['morphable_model_class'],
            ]);

            $morph->refresh();
            $this->assertInstanceOf($relation['morphable_model_class'], $morph->{$relation['morph_relation']});
        }

    }
}
