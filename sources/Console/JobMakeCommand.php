<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Foundation\Console\JobMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;

class JobMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:job
        {name : The name of the class}
        {--addon= : The name of the addon}
        {--queued : Indicates that job should be queued}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new job class';

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
