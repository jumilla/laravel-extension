<?php

use LaravelPlus\Extension\ConsoleKernel;
use Illuminate\Contracts\Events\Dispatcher;
use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;
use LaravelPlus\Extension\Generators\GeneratorCommandRegistrar;

class ConsoleKernelTests extends TestCase
{
    public function test_bootstrapMethod()
    {
        $app = $this->createApplication();
        $dispatcher = $this->createMock(Dispatcher::class);
        $addonEnvironment = $this->createMock(AddonEnvironment::class);
        $kernel = new ConsoleKernelStub($app, $dispatcher);

        $addonEnvironment->shouldReceive('addonConsoleCommands')->andReturn([])->once();

        $app[AddonEnvironment::class] = $addonEnvironment;

        $kernel->bootstrap();
    }
}
