<?php


namespace Thomasdominic\ModelsTestor\Traits;


trait HasManyMorphRelationsTestable
{
    /** @test */
    public function a_morphable_model_can_be_added_for_a_morph_model()
    {
        if(property_exists($this,'hasManyMorphRelations') && count($this->hasManyMorphRelations) > 0) {
            foreach ($this->hasManyMorphRelations as $relation) {

                $morphable = factory($relation['morphable_model_class'])->create();
                $morphable->{$relation['morph_relation']}()->save(factory($relation['morph_model_class'])->make());

                $morphable->refresh();
                $this->assertInstanceOf($relation['morph_model_class'], $morphable->{$relation['morph_relation']}->first());
            }
        }
    }
}