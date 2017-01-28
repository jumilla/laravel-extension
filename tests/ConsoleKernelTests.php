<?php

use LaravelPlus\Extension\ConsoleKernel;

class ConsoleKernelTests extends TestCase
{
    public function test_canInstanciate()
    {
        $app = $this->createApplication();

        new ConsoleKernel($app, $app['events']);

        Assert::isTrue(true);
    }
}
