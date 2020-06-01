<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;

class MorphModel extends Model
{
    protected $fillable = ['name', 'morph_modelable_type', 'morph_modelable_id'];
    public $timestamps = false;

    public function morph_modelable()
    {
        return $this->morphTo();
    }
}
