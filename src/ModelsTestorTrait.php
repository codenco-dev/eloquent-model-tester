<?php

namespace Thomasdominic\ModelsTestor;


trait ModelsTestorTrait
{

    public function modelTestable(string $modelClass, ?string $table = null)
    {
        return (new ModelsTestor($modelClass,$table));
    }

    public function tableTestable(string $table)
    {
        return (new ModelsTestor(null,$table));
    }

}
