<?php

namespace CodencoDev\EloquentModelTester;

trait HasModelTester
{
    public function modelTestable(string $modelClass, ?string $table = null): ModelTester
    {
        return ModelTesterFacade::create($modelClass, $table);
    }

    public function tableTestable(string $table): ModelTester
    {
        return ModelTesterFacade::create(null, $table);
    }
}
