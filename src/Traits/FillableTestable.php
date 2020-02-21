<?php


namespace Thomasdominic\ModelsTestor\Traits;


trait FillableTestable
{
    /** @test */
    public function a_model_can_be_create_with_properties()
    {
        if (property_exists($this, 'toBeInFillableModel') && property_exists($this, 'toBeInFillableProperty')) {
            $t = collect(array_flip($this->toBeInFillableProperty))->transform(function ($item, $key) {
                return 'value_for_test';
            });

            $model = (new $this->toBeInFillableModel)->fill($t->toArray());
            $this->assertEquals([], collect($t->toArray())->diffAssoc($model->toArray())->toArray());
        }

    }
}