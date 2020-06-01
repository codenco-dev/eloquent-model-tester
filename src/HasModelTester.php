<?php

namespace CodencoDev\EloquentModelTester;

use CodencoDev\EloquentModelTester\ModelTesterFacade;

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
