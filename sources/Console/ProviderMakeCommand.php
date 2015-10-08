<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Foundation\Console\ProviderMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class ProviderMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:provider
        {name : The name of the class}
        {--addon= : The name of the addon}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new service provider class';

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

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return parent::getStub();
    }
}
