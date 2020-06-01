<?php

namespace CodencoDev\EloquentModelTester;

trait HasModelTester
{
    public function modelTestable(string $modelClass, ?string $table = null)
    {
        return ModelTesterFacade::create($modelClass, $table);
    }

    public function tableTestable(string $table)
    {
        return ModelTesterFacade::create(null, $table);
    }
}
