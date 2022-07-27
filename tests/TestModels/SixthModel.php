<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\SixthModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SixthModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'name', 'isAdmin'];

    protected $guarded = ['isAdmin'];

    public $timestamps = true;

    protected static function newFactory()
    {
        return SixthModelFactory::new();
    }
}
