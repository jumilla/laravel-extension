<?php

use LaravelPlus\Extension\ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;

class ConsoleKernelTest extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $dispatcher = $this->createMock(Dispatcher::class);
        $command = new ConsoleKernelStub($app, $dispatcher);

        Assert::isInstanceOf(ConsoleKernel::class, $command);
    }
}
