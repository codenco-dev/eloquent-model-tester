<?php

namespace CodencoDev\EloquentModelTester;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class ModelTester extends TestCase
{
    private ?string $tested;

    private ?string $table;

    public function create(?string $tested, ?string $table = null): self
    {
        $this->tested = $tested;
        $this->table = $table;

        return $this;
    }

    public function getModel(): ?string
    {
        throw_if(
            is_null($this->tested) || ! $this->isModelClass($this->tested),
            new \Exception('You have to use a Eloquent Model')
        );

        return $this->tested;
    }

    public function getTable(): string
    {
        throw_if(
            ! $this->isExistingTable($this->table),
            new \Exception('You have to use an existing table')
        );

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

    public function assertHasTimestampsColumns()
    {
        return $this->assertHasColumns(['created_at', 'updated_at']);
    }

    public function assertHasSoftDeleteTimestampColumns()
    {
        return $this->assertHasColumns('deleted_at');
    }

    public function assertHasColumns(array | string ...$columns): self
    {
        $columns = $this->getArrayParameters(...$columns);
        collect($columns)->each(function ($column) {
            $this->assertTrue(
                in_array($column, Schema::getColumnListing($this->getTable())),
                sprintf(
                    'Column %s isn\'t a column of table %s.',
                    $column,
                    $this->getTable()
                )
            );
        });

        return $this;
    }

    public function assertCanFillables(array | string ...$columns): self
    {
        $columns = $this->getArrayParameters(...$columns);
        $modelClass = $this->getModel();
        $modelObject = new $modelClass;
        $notFillable = collect([]);
        foreach ($columns as $column) {
            if (! $modelObject->isFillable($column)) {
                $notFillable->push($column);
            }
        }
        $this->assertEquals(
            [],
            $notFillable->toArray(),
            sprintf(
                'Column %s isn\'t mass fillable.',
                $notFillable->implode(', ')
            )
        );

        return $this;
    }

    public function assertHasHasOneRelation(string $related, ?string $relation = null, ?array $defaultRelatedValue = []): self
    {
        $relation = $relation ?: $this->getHasOneRelationName($related);

        $modelInstance = $this->getModel()::factory()->create();

        try {
            $relatedInstance = $modelInstance->{$relation}()->save($related::factory()->make($defaultRelatedValue));
            $modelInstance->refresh();

            $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
            $this->assertEquals(1, $modelInstance->{$relation}->count());
            $this->assertInstanceOf(Collection::class, $modelInstance->{$relation});
            $this->assertInstanceOf($related, $modelInstance->{$relation}->first());
        } catch (\Exception $e) {
            $this->assertThat('Has Has One Relation', self::isTrue(), sprintf(
                'There is a problem with the HasOneRelation %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasHasManyRelation(string $related, ?string $relation = null, ?array $defaultRelatedValue = []): self
    {
        $relation = $relation ?: $this->getHasManyRelationName($related);

        $modelInstance = $this->getModel()::factory()->create();

        try {
            $relatedInstance = $modelInstance->{$relation}()->save($related::factory()->make($defaultRelatedValue));
            $modelInstance->refresh();

            $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
            $this->assertEquals(1, $modelInstance->{$relation}->count());
            $this->assertInstanceOf(Collection::class, $modelInstance->{$relation});
            $this->assertInstanceOf($related, $modelInstance->{$relation}->first());
        } catch (\Exception $e) {
            $this->assertThat('Has Has Many Relation', self::isTrue(), sprintf(
                'There is a problem with the HasManyRelation %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasHasManyThroughRelation(
        string $related,
        string $through,
        ?string $relation = null,
        ?string $firstKey = null,
        ?string $secondKey = null,
        ?string $localKey = null,
        ?string $secondLocalKey = null,
    ): self {
        $relation = $relation ?: $this->getHasManyRelationName($related);

        $modelInstance = $this->getModel()::factory()->create();
        $throughInstance = $through::factory()->create();
        $firstKey = $firstKey ?: $modelInstance->getForeignKey();
        $secondKey = $secondKey ?: $throughInstance->getForeignKey();
        $localKey = $localKey ?: $modelInstance->getKeyName();
        $secondLocalKey = $secondLocalKey ?: $throughInstance->getKeyName();

        try {
            $throughInstance = $through::factory()->create([$firstKey => $modelInstance->{$localKey}]);
            $relatedInstance = $related::factory()->create([$secondKey => $throughInstance->{$secondLocalKey}]);
            $modelInstance->refresh();

            $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
            $this->assertEquals(1, $modelInstance->{$relation}->count());
            $this->assertInstanceOf(Collection::class, $modelInstance->{$relation});
            $this->assertInstanceOf($related, $modelInstance->{$relation}->first());
        } catch (\Exception $e) {
            $this->assertThat('Has Has Many Through Relation', self::isTrue(), sprintf(
                'There is a problem with the HasManyThroughRelation %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasBelongsToRelation(string $related, ?string $relation = null, ?string $foreignKey = null): self
    {
        $relation = $relation ?: $this->getBelongsToRelationName($related);

        $relatedInstance = $related::factory()->create();
        $foreignKey = $foreignKey ?: $relatedInstance->getForeignKey();

        $modelInstance = $this->getModel()::factory()->create([$foreignKey => $relatedInstance->id]);
        $relatedInstance2 = $related::factory()->create();
        $modelInstance2 = $this->getModel()::factory()->make();

        try {
            $modelInstance2->{$relation}()->associate($relatedInstance2)->save();

            $this->assertEquals($modelInstance->{$relation}->id, $relatedInstance->id);
            $this->assertInstanceOf($related, $modelInstance->{$relation});
            $this->assertEquals($modelInstance2->{$foreignKey}, $relatedInstance2->id);
            $this->assertInstanceOf($related, $modelInstance2->{$relation});
        } catch (\Exception $e) {
            $this->assertThat('Has Belongs To Relation', self::isTrue(), sprintf(
                'There is a problem with the BelongsToRelation %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasManyToManyRelation(string $related, ?string $relation = null, ?array $pivot_value = null): self
    {
        $relation = $relation ?: $this->getManyToManyRelationName($related);

        $modelInstance = $this->getModel()::factory()->create();
        $relatedInstance = $related::factory()->create();

        try {
            $modelInstance->{$relation}()->attach($relatedInstance, $pivot_value ?? []);

            $this->assertTrue($modelInstance->{$relation}->contains($relatedInstance));
            $this->assertEquals($relatedInstance->id, $modelInstance->{$relation}->first()->id);
            $this->assertEquals(1, $modelInstance->{$relation}->count());
            $this->assertInstanceOf($related, $modelInstance->{$relation}->first());
        } catch (\Exception $e) {
            $this->assertThat('Has Many To Many Relation', self::isTrue(), sprintf(
                'There is a problem with the ManyToManyRelation %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasHasManyMorphRelation(string $related, ?string $relation = null): self
    {
        $relation = $relation ?: $this->getHasManyRelationName($related);

        $instance = $this->getModel()::factory()->create();

        try {
            $instance->{$relation}()->save($related::factory()->make());
            $instance->refresh();

            $this->assertInstanceOf($related, $instance->{$relation}->first());
        } catch (\Exception $e) {
            $this->assertThat('Has Has Many Morph Relation', self::isTrue(), sprintf(
                'There is a problem with the HasManyMorph %s : %s',
                $relation,
                $e->getMessage()
            ));
        }

        return $this;
    }

    public function assertHasBelongsToMorphRelation(string $related, string $relation, ?string $type = null, ?string $id = null): self
    {
        [$type, $id] = $this->getMorphs($relation, $type, $id);

        $instance = $related::factory()->create();
        $morph = $this->getModel()::factory()->create([
            $id => $instance->id,
            $type => $related,
        ]);
        $morph->refresh();

        $this->assertInstanceOf($related, $morph->{$relation}, sprintf(
            'There is a problem with the HasManyMorph %s',
            $relation
        ));

        return $this;
    }

    public function getBelongsToRelationName(string $related): string
    {
        return Str::snake(class_basename($related));
    }

    public function getHasOneRelationName(string $related): string
    {
        return Str::singular(Str::snake(class_basename($related)));
    }

    public function getHasManyRelationName(string $related): string
    {
        return Str::plural(Str::snake(class_basename($related)));
    }

    public function getManyToManyRelationName(string $related): string
    {
        return Str::plural(Str::snake(class_basename($related)));
    }

    /**
     * @param $groups
     * @param mixed $columns
     * @return array
     */
    public function getArrayParameters(array | string ...$args): array
    {
        $params = null;
        foreach ($args as $arg) {
            $params = array_merge(
                (array)$params,
                Arr::wrap($arg)
            );
        }

        return $params;
    }

    private function getMorphs(string $name, ?string $type, ?string $id): array
    {
        return [$type ?: $name.'_type', $id ?: $name.'_id'];
    }
}
