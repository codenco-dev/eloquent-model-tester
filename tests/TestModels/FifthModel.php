<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\FifthModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FifthModel extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'second_model_id'];

    protected $guarded = ['is_admin'];

    public $timestamps = false;

    protected static function newFactory()
    {
        return FifthModelFactory::new();
    }
}
