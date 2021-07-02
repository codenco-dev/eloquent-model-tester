<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\ThirdModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ThirdModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function second_models(): BelongsToMany
    {
        return $this->belongsToMany(SecondModel::class, 'second_model_third_model', 'third_model_id', 'second_model_id');
    }

    protected static function newFactory()
    {
        return ThirdModelFactory::new();
    }
}
