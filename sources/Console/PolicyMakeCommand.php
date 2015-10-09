<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Foundation\Console\PolicyMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class PolicyMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:policy
        {name : The name of the class}
        {--addon= : The name of the addon}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new policy class';

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
