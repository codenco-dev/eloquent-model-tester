<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\FirstModelFactory;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeTheScope1(Builder $query): Builder
    {
        return $query->whereRaw('1=1');
    }

    public function scopeTheScope2(Builder $query, $param1): Builder
    {
        return $query->whereRaw('1=1');
    }

    public function scopeTheScope3(Builder $query, $param1 = ''): Builder
    {
        return $query->whereRaw('1=1');
    }

    public function scopeTheScope4(Builder $query, $param1, $param2): Builder
    {
        return $query->whereRaw('1=1');
    }
}
