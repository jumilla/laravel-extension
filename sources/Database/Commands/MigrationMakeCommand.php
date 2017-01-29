<?php

namespace LaravelPlus\Extension\Database\Commands;

use Jumilla\Versionia\Laravel\Commands\MigrationMakeCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class MigrationMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:migration
        {name : The name of the class}
        {--a|addon= : The name of the addon}
        {--create= : The table to be created}
        {--update= : The table to be updated}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new migration class';

    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStubDirectory(__DIR__.'/../stubs');
    }

    /**
     * Get the default namespace for the class.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return $this->getRootNamespace().'\\'.($this->onAddon() ? 'Migrations' : 'Database\\Migrations');
    }
}
