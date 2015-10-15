<?php

use LaravelPlus\Extension\HttpKernel;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;

class HttpKernelTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $kernel = new HttpKernelStub($app, new Router(new Dispatcher));

        Assert::isInstanceOf(HttpKernel::class, $kernel);
    }
}
