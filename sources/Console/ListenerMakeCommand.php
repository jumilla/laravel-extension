<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Foundation\Console\ListenerMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class ListenerMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:listener
        {name : The name of the class}
        {--addon= : The name of the addon}
        {--event= : The event class the being listened for}
        {--queued : Indicates the event listener should be queued}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new event listener class';

    /**
     * Get the destination class base path.
     *
     * @param \LaravelPlus\Extension\Addons\Addon $addon
     *
     * @return string
     */
    protected function getBasePath(Addon $addon)
    {
        return $addon->path('classes');
    }
}
