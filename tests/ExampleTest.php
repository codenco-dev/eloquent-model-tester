<?php

namespace Thomasdominic\ModelsTestor\Tests;

use Orchestra\Testbench\TestCase;
use Thomasdominic\ModelsTestor\ModelsTestorServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ModelsTestorServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
