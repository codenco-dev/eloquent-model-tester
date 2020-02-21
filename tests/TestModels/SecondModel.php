<?php


namespace Thomasdominic\ModelsTestor\Tests\TestModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SecondModel extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'first_model_id'];

    public function first_model(): BelongsTo
    {
        return $this->belongsTo(FirstModel::class,'first_model_id','id');
    }

    public function third_models():BelongsToMany
    {
        return $this->belongsToMany(ThirdModel::class,'second_model_third_model','second_model_id','third_model_id');
    }
}