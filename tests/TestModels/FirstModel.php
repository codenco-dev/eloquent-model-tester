<?php


namespace Thomasdominic\ModelsTestor\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FirstModel extends Model
{
    protected $fillable = ['id','name'];

    public $timestamps = false;

    public function second_models():HasMany
    {
        return $this->hasMany(SecondModel::class,'first_model_id','id');
    }


}