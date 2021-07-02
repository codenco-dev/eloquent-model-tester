<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\MorphModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MorphModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'morph_modelable_type', 'morph_modelable_id'];
    public $timestamps = false;

    public function morph_modelable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return MorphModelFactory::new();
    }
}
