<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\FirstModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FirstModel extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public $timestamps = false;

    public function second_models(): HasMany
    {
        return $this->hasMany(SecondModel::class, 'first_model_id', 'id');
    }

    protected static function newFactory()
    {
        return FirstModelFactory::new();
    }
}
