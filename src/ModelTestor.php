<?php

namespace Thomasdominic\EloquentModelTestor;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class ModelTestor extends TestCase
{
    private ?string $tested;

    private ?string $table;

    public function __construct(?string $tested, ?string $table = null)
    {
        parent::__construct();
        $this->tested = $tested;
        $this->table = $table;
    }

    public function getModel(): ?string
    {
        throw_if(is_null($this->tested) || ! $this->isModelClass($this->tested),
            new \Exception('You have to use a Eloquent Model'));

        return $this->tested;
    }

    public function getTable(): string
    {
        throw_if(! $this->isExistingTable($this->table),
            new \Exception('You have to use an existing table'));

        return $this->getModelTable();
    }

    public function getModelTable(): string
    {
        if (! empty($this->table)) {
            return $this->table;
        }

        $modelClass = $this->getModel();

        return (new $modelClass)->getTable();
    }

    public function isModelClass(?string $modelClass = null): bool
    {
        if (! is_null($modelClass)) {
            return (new $modelClass) instanceof Model;
        } else {
            return (new $this->tested) instanceof Model;
        }
    }

    public function isExistingTable(?string $tableName = null): bool
    {
        if (! is_null($tableName)) {
            return Schema::hasTable($tableName);
        } else {
            return Schema::hasTable($this->getModelTable());
        }
    }

    public function assertHasColumns(array $columns): self
    {
        collect($columns)->each(function ($column) {
            $this->assertTrue(in_array($column, Schema::getColumnListing($this->getTable())));
        });

        return $this;
    }

    public function assertCanFillables(array $columns = []): self
    {
        $modelClass = $this->getModel();
        $this->assertEquals([], collect($columns)->diff((new $modelClass)->getFillable())->toArray());

        return $this;
    }

    public function assertHasHasManyRelation(string $related, ?string $relation = null): self
    {
        $relation = $relation ?: $this->getHasManyRelationName($related);

        $modelInstance = factory($this->getModel())->create();
        $relatedInstance = $modelInstance->{$relation}()->save(factory($related)->make());
        $modelInstance->refresh();

        $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
        $this->assertEquals(1, $modelInstance->{$relation}->count());
        $this->assertInstanceOf(Collection::class, $modelInstance->{$relation});
        $this->assertInstanceOf($related, $modelInstance->{$relation}->first());

        return $this;
    }

    public function assertHasBelongsToRelation(string $related, ?string $relation = null, ?string $foreignKey = null): self
    {
        $relation = $relation ?: $this->getBelongsToRelationName($related);

        $relatedInstance = factory($related)->create();
        $foreignKey = $foreignKey ?: $relatedInstance->getForeignKey();

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

    public function assertHasManyToManyRelation(string $related, ?string $relation = null): self
    {
        $relation = $relation ?: $this->getManyToManyRelationName($related);

        $modelInstance = factory($this->getModel())->create();
        $relatedInstance = factory($related)->create();
        $modelInstance->{$relation}()->attach($relatedInstance);

        $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
        $this->assertEquals($relatedInstance->id, $modelInstance->{$relation}->first()->id);
        $this->assertEquals(1, $modelInstance->{$relation}->count());
        $this->assertInstanceOf($related, $modelInstance->{$relation}->first());

        return $this;
    }

    public function assertHasHasManyMorphRelation(string $related, ?string $relation = null): self
    {
        $relation = $relation ?: $this->getHasManyRelationName($related);

        $instance = factory($this->getModel())->create();
        $instance->{$relation}()->save(factory($related)->make());
        $instance->refresh();

        $this->assertInstanceOf($related, $instance->{$relation}->first());

        return $this;
    }

    public function assertHasBelongsToMorphRelation(string $related, string $name, ?string $type = null, ?string $id = null): self
    {
        [$type, $id] = $this->getMorphs($name, $type, $id);

        $instance = factory($related)->create();
        $morph = factory($this->getModel())->create([
            $id   => $instance->id,
            $type => $related,
        ]);
        $morph->refresh();

        $this->assertInstanceOf($related, $morph->{$name});

        return $this;
    }

    public function getBelongsToRelationName(string $related): string
    {
        return Str::snake(class_basename($related));
    }

    public function getHasManyRelationName(string $related): string
    {
        return Str::plural(Str::snake(class_basename($related)));
    }

    public function getManyToManyRelationName(string $related): string
    {
        return Str::plural(Str::snake(class_basename($related)));
    }

    private function getMorphs(string $name, ?string $type, ?string $id): array
    {
        return [$type ?: $name.'_type', $id ?: $name.'_id'];
    }
}
