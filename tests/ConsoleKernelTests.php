<?php

use LaravelPlus\Extension\ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;
use LaravelPlus\Extension\Addons\Environment as AddonEnvironment;
use LaravelPlus\Extension\Generators\GeneratorCommandRegistrar;

class ConsoleKernelTests extends TestCase
{
    public function test_bootstrapMethod()
    {
        $app = $this->createApplication();
        $dispatcher = $this->createMock(Dispatcher::class);
        $addonEnvironment = $this->createMock(AddonEnvironment::class);
        $kernel = new ConsoleKernelStub($app, $dispatcher);

        $addonEnvironment->shouldReceive('getAddonConsoleCommands')->andReturn([])->once();

        $app[AddonEnvironment::class] = $addonEnvironment;

        $kernel->bootstrap();
    }
}
