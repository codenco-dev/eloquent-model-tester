<?php


namespace Thomasdominic\ModelsTestor\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FirstModel extends Model
{
    protected $fillable = ['id','name'];

    public $timestamps = false;

    public function second_models():HasMany
    {
        return $this->hasMany(SecondModel::class,'first_model_id','id');
    }

    public function morph_models(): MorphMany
    {
        return $this->morphMany(MorphModel::class, 'morph_modelable');
    }
}