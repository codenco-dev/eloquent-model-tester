<?php


namespace Thomasdominic\ModelsTestor\Traits;


trait ManyToManyRelationsTestable
{
    /** @test */
    public function a_model_has_many_related_with_many_to_many_relation()
    {
        if(property_exists($this,'manyToManyRelations') && count($this->manyToManyRelations) > 0) {

            foreach ($this->manyToManyRelations as $relation) {

                $model = factory($relation['model_class'])->create();
                $related = factory($relation['relation_class'])->create();
                $model->{$relation['relation_name']}()->attach($related);

                $this->assertTrue($model->{$relation['relation_name']}->contains($related));
                $this->assertEquals($related->id,$model->{$relation['relation_name']}->first()->id);
                $this->assertEquals(1,$model->{$relation['relation_name']}->count());
                $this->assertInstanceOf($relation['relation_class'],$model->{$relation['relation_name']}->first());

            }

        }else{
            $this->assertTrue(true);
        }


    }
}