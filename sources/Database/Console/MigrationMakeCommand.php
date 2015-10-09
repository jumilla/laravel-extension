<?php

namespace LaravelPlus\Extension\Database\Console;

use Illuminate\Console\GeneratorCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Console\GeneratorCommandTrait;

class MigrationMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:migration
        {name : The name of the class}
        {--addon= : The name of the addon}
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
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

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
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->template($stub, [
            'namespace' => $this->getNamespace($name),
            'class' => str_replace($this->getNamespace($name).'\\', '', $name),
            'table' => $this->option('create') ?: $this->option('update'),
        ]);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Database\\Migrations';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->stub) {
            return $this->stub;
        }

        if ($this->option('create')) {
            return __DIR__.'/stubs/migration-create.stub';
        } elseif ($this->option('update')) {
            return __DIR__.'/stubs/migration-update.stub';
        } else {
            return __DIR__.'/stubs/migration-blank.stub';
        }
    }
}
