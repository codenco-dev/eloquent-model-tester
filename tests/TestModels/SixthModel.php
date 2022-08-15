<?php

namespace CodencoDev\EloquentModelTester\Tests\TestModels;
use Database\Factories\SixthModelFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SixthModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'name', 'first_model_id', 'is_admin'];

    protected $guarded = ['is_admin'];

    public $timestamps = true;

    protected static function newFactory()
    {
        return SixthModelFactory::new();
    }

    public function first_model(): BelongsTo
    {
        return $this->belongsTo(FirstModel::class, 'first_model_id', 'id');
    }

    public function morphed(): MorphOne
    {
        return $this->morphOne(MorphModel::class, 'morph_modelable');
    }
}
