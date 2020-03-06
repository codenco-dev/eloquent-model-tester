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

    public function getModel(): string
    {
        throw_if(is_null($this->model_testable) || !$this->isModelClass($this->model_testable),
            new \Exception("You have to use a Eloquent Model"));

        return $this->model_testable;
    }

    public function getTable(): string
    {
        throw_if(!$this->isExistingTable(),
            new \Exception("You have to use an existing table"));

        return $this->getModelTable();
    }

    public function getModelTable(): string
    {
        if (!empty($this->table)) {
            return $this->table;
        }

        $modelClass = $this->getModel();

        return (new $modelClass)->getTable();
    }

    public function isModelClass(?string $modelClass = null): bool
    {
        if (!is_null($modelClass)) {
            return ((new $modelClass) instanceof Model);
        } else {
            return ((new $this->model_testable) instanceof Model);
        }

    }

    public function isExistingTable(?string $tableName = null): bool
    {
        if (!is_null($tableName)) {
            return Schema::hasTable($tableName);
        } else {
            return Schema::hasTable($this->getModelTable());
        }
    }


    public function assertHasColumns(array $columns): ModelsTestor
    {

        collect($columns)->each(function ($column) {
            $this->assertTrue(in_array($column, Schema::getColumnListing($this->getTable())));
        });

        return $this;
    }


    public function assertCanFillables(array $fillable = []): ModelsTestor
    {

        $modelClass = $this->getModel();
        $this->assertEquals([], collect($fillable)->diff((new $modelClass)->getFillable())->toArray());

        return $this;
    }

    public function assertHasHasManyRelation(string $related, string $relation): ModelsTestor
    {

        $modelInstance = factory($this->getModel())->create();
        $relatedInstance = $modelInstance->{$relation}()->save(factory($related)->make());
        $modelInstance->refresh();

        $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
        $this->assertEquals(1, $modelInstance->{$relation}->count());
        $this->assertInstanceOf(Collection::class, $modelInstance->{$relation});
        $this->assertInstanceOf($related, $modelInstance->{$relation}->first());

        return $this;
    }

    public function assertHasBelongsToRelation(string $related, string $relation, string $foreignKey): ModelsTestor
    {

        $relatedInstance = factory($related)->create();
        $modelInstance = factory($this->getModel())->create([$foreignKey => $relatedInstance->id]);
        $relatedInstance2 = factory($related)->create();
        $modelInstance2 = factory($this->getModel())->make();
        $modelInstance2->{$relation}()->associate($relatedInstance2)->save();

        $this->assertEquals($modelInstance->{$relation}->id, $relatedInstance->id);
        $this->assertInstanceOf($related, $modelInstance->{$relation});
        $this->assertEquals($modelInstance2->{$foreignKey}, $relatedInstance2->id);
        $this->assertInstanceOf($related, $modelInstance2->{$relation});

        return $this;
    }

    public function assertHasManyToManyRelation(string $related, string $relation): ModelsTestor
    {

        $modelInstance = factory($this->getModel())->create();
        $relatedInstance = factory($related)->create();
        $modelInstance->{$relation}()->attach($relatedInstance);

        $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
        $this->assertEquals($relatedInstance->id, $modelInstance->{$relation}->first()->id);
        $this->assertEquals(1, $modelInstance->{$relation}->count());
        $this->assertInstanceOf($related, $modelInstance->{$relation}->first());

        return $this;
    }

    public function assertHasHasManyMorphRelation(string $related, string $name): ModelsTestor
    {

        $instance = factory($this->getModel())->create();
        $instance->{$name}()->save(factory($related)->make());
        $instance->refresh();

        $this->assertInstanceOf($related, $instance->{$name}->first());

        return $this;
    }

    public function assertHasBelongsToMorphRelation(string $related, string $name): ModelsTestor
    {

        $instance = factory($related)->create();
        $morph = factory($this->getModel())->create([
            $name.'_id'   => $instance->id,
            $name.'_type' => $related,
        ]);
        $morph->refresh();

        $this->assertInstanceOf($related, $morph->{$name});

        return $this;
    }
}
