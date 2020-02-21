<?php


namespace Thomasdominic\ModelsTestor\Traits;


trait BelongToRelationsTestable
{
    /** @test */
    public function a_model_belong_to_a_related()
    {
        if(property_exists($this,'belongToRelations') && count($this->belongToRelations) > 0) {
            foreach ($this->belongToRelations as $relation) {
                $related = factory($relation['relation_class'])->create();
                $model = factory($relation['model_class'])->create([$relation['relation_foreign_key'] => $related->id]);

                $this->assertEquals($model->{$relation['relation_name']}->id, $related->id);
                $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']});
            }
        }else{
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function a_model_belong_to_a_related_by_relation()
    {
        if(property_exists($this,'belongToRelations') && count($this->belongToRelations) > 0) {
            foreach ($this->belongToRelations as $relation) {
                $related = factory($relation['relation_class'])->create();
                $model = factory($relation['model_class'])->make();
                $model->{$relation['relation_name']}()->associate($related)->save();

                $this->assertEquals($model->{$relation['relation_foreign_key']}, $related->id);
                $this->assertInstanceOf($relation['relation_class'], $model->{$relation['relation_name']});
            }
        }else{
            $this->assertTrue(true);
        }
    }

}