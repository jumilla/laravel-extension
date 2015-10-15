<?php

use LaravelPlus\Extension\ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;

class ConsoleKernelTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $dispatcher = $this->createMock(Dispatcher::class);
        $kernel = new ConsoleKernelStub($app, $dispatcher);

        Assert::isInstanceOf(ConsoleKernel::class, $kernel);

//        $kernel->bootstrap();
    }
}
