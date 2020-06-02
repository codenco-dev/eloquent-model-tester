<?php

namespace CodencoDev\EloquentModelTester;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodencoDev\EloquentModelTester\Skeleton\SkeletonClass
 */
class ModelTesterFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ModelTester::class;
    }
}
