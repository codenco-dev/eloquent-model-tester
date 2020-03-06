<?php

namespace Thomasdominic\EloquentModelTestor;

trait HasModelTestor
{
    public function modelTestable(string $modelClass, ?string $table = null)
    {
        return new ModelTestor($modelClass, $table);
    }

    public function tableTestable(string $table)
    {
        return new ModelTestor(null, $table);
    }
}
