<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;

use Database\Factories\SixthModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SixthModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['id', 'name', 'first_model_id'];

    public function first_model(): BelongsTo
    {
        return $this->belongsTo(FirstModel::class, 'first_model_id', 'id');
    }

    public function morphed(): MorphOne
    {
        return $this->morphOne(MorphModel::class, 'morph_modelable');
    }

    protected static function newFactory()
    {
        return SixthModelFactory::new();
    }
}
