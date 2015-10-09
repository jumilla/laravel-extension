<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Foundation\Console\TestMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class TestMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:test
        {name : class name}
        {--addon= : Addon name}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new test class';

    /**
     * Get the destination class base path.
     *
     * @param \LaravelPlus\Extension\Addons\Addon $addon
     *
     * @return string
     */
    protected function getBasePath(Addon $addon)
    {
        return $addon->path('tests');
    }
}
