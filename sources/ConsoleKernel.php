<?php

namespace LaravelPlus\Extension;

use Illuminate\Foundation\Console\Kernel;
use LaravelPlus\Extension\Addons\Environment as AddonEnvironment;

abstract class ConsoleKernel extends Kernel
{
    /**
     * Bootstrap the application for Console.
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $registrar = new GeneratorCommandRegistrar($this->app);

        $this->commands = array_merge($this->commands, $registrar->register(), app(AddonEnvironment::class)->getAddonConsoleCommands());
    }
}
