<?php


namespace Thomasdominic\ModelsTestor\Traits;


use Illuminate\Database\Eloquent\Collection;

trait HasManyRelationsTestable
{
    /** @test */
    public function a_model_has_many_related_by_relation()
    {
        if (property_exists($this, 'hasManyRelations') && count($this->hasManyRelations) > 0) {

            foreach ($this->hasManyRelations as $relation) {
                $model = factory($relation['model_class'])->create();

                $related = $model->{$relation['relation_name']}()->save(factory($relation['relation_class'])->make());
                $model->refresh();
                $this->assertTrue($model->{$relation['relation_name']}->contains($related));
                $this->assertEquals(1, $model->{$relation['relation_name']}->count());

                $this->assertInstanceOf(Collection::class, $model->{$relation['relation_name']});
                $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());
            }

        } else {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function a_model_has_many_related()
    {
        if (property_exists($this, 'hasManyRelations') && count($this->hasManyRelations) > 0) {

            foreach ($this->hasManyRelations as $relation) {
                $model = factory($relation['model_class'])->create();
                $related
                    = factory($relation['relation_class'])->create([$relation['relation_foreign_key'] => $model->id]);

                $this->assertTrue($model->{$relation['relation_name']}->contains($related));
                $this->assertEquals(1, $model->{$relation['relation_name']}->count());
                $this->assertInstanceOf(Collection::class, $model->{$relation['relation_name']});
                $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']}->first());
            }

        } else {
            $this->assertTrue(true);
        }
    }
}