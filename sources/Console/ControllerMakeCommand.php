<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Routing\Console\ControllerMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class ControllerMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:controller
        {name : The name of the class}
        {--addon= : The name of the addon}
        {--plain : Generate an empty controller class}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new resource controller class';

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
