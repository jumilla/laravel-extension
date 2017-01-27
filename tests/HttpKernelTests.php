<?php

use LaravelPlus\Extension\HttpKernel;

class HttpKernelTests extends TestCase
{
    public function test_canInstanciate()
    {
        $app = $this->createApplication();

        new HttpKernel($app, $app['router']);

        Assert::isTrue(true);
    }
}
