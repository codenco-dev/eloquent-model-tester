<?php


namespace Thomasdominic\ModelsTestor\Traits;


trait BelongToMorphRelationsTestable
{
    /** @test */
    public function a_morph_model_can_be_added_for_a_morphable_model()
    {
        if(property_exists($this,'belongToMorphRelations') && count($this->belongToMorphRelations) > 0) {
            foreach ($this->belongToMorphRelations as $relation) {
                $morphable = factory($relation['morphable_model_class'])->create();
                $morph = factory($relation['morph_model_class'])->create([
                    $relation['morph_relation'].'_id'   => $morphable->id,
                    $relation['morph_relation'].'_type' => $relation['morphable_model_class'],
                ]);

                $morph->refresh();
                $this->assertInstanceOf($relation['morphable_model_class'], $morph->{$relation['morph_relation']});
            }
        }
    }
}