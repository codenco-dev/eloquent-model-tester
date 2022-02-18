<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\FirstModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class FirstModel extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public $timestamps = false;

    public function second_models(): HasMany
    {
        return $this->hasMany(SecondModel::class, 'first_model_id', 'id');
    }

    public function fifth_models(): HasManyThrough
    {
        return $this->hasManyThrough(FifthModel::class, SecondModel::class, 'first_model_id', 'second_model_id');
    }

    protected static function newFactory()
    {
        return FirstModelFactory::new();
    }
}
