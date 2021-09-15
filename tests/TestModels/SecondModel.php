<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\SecondModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SecondModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'name', 'first_model_id'];

    public function first_model(): BelongsTo
    {
        return $this->belongsTo(FirstModel::class, 'first_model_id', 'id');
    }

    public function third_models(): BelongsToMany
    {
        return $this->belongsToMany(ThirdModel::class, 'second_model_third_model', 'second_model_id', 'third_model_id');
    }

    public function fourth_models(): BelongsToMany
    {
        return $this->belongsToMany(FourthModel::class, 'fourth_model_second_model', 'second_model_id', 'fourth_model_id')->withPivot(['pivot_field']);
    }

    public function morph_models(): MorphMany
    {
        return $this->morphMany(MorphModel::class, 'morph_modelable');
    }

    protected static function newFactory()
    {
        return SecondModelFactory::new();
    }
}
