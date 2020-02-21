<?php


namespace Thomasdominic\ModelsTestor\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ThirdModel extends Model
{
    public $timestamps = false;

    protected $fillable = ['id','name'];

    public function second_models():BelongsToMany
    {
        return $this->belongsToMany(SecondModel::class,'second_model_third_model','third_model_id','second_model_id');
    }
}