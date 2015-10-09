<?php

namespace LaravelPlus\Extension\Database\Console;

use Illuminate\Console\GeneratorCommand as BaseCommand;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Console\GeneratorCommandTrait;

class SeederMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:seeder
        {name : The name of the class}
        {--addon= : The name of the addon}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new seeder class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seeder';

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
        return $rootNamespace.'\\Database\\Seeds';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->stub ?: __DIR__.'/stubs/seeder.stub';
    }
}
