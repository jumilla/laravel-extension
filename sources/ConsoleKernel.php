<?php

namespace LaravelPlus\Extension;

use Illuminate\Foundation\Console\Kernel;

abstract class ConsoleKernel extends Kernel
{
    /**
     * Bootstrap the application for Console.
     *
     * @return void
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $this->commands = array_merge($this->commands, Application::getAddonConsoleCommands());
    }
}
