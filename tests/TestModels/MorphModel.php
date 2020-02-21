<?php


namespace Thomasdominic\ModelsTestor\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;

class MorphModel extends Model
{
    public $timestamps = false;

    public function morph_modelable()
    {
        return $this->morphTo();
    }
}