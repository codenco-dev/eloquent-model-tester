<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\FourthModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FourthModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function second_models(): BelongsToMany
    {
        return $this->belongsToMany(SecondModel::class, 'fourth_model_second_model', 'fourth_model_id', 'second_model_id')->withPivot(['pivot_field']);
    }

    protected static function newFactory()
    {
        return FourthModelFactory::new();
    }
}
