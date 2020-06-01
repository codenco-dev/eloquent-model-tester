<?php

namespace CodencoDev\EloquentModelTester;

use CodencoDev\EloquentModelTester\ModelTesterFacade as ModelTester;

trait HasModelTester
{
    public function modelTestable(string $modelClass, ?string $table = null)
    {
        return ModelTester::create($modelClass, $table);
    }

    public function tableTestable(string $table)
    {
        return ModelTester::create(null, $table);
    }
}
