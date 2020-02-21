<?php

namespace Thomasdominic\ModelsTestor;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thomasdominic\ModelsTestor\Skeleton\SkeletonClass
 */
class ModelsTestorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'models-testor';
    }
}
