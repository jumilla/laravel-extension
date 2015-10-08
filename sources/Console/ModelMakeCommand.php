<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\GeneratorCommand as BaseCommand;
use Illuminate\Support\Str;
use LaravelPlus\Extension\Addons\Addon;

class ModelMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var stringphp
     */
    protected $signature = 'make:model
        {name : The name of the class}
        {--addon= : The name of the addon}
        {--migration= : Create a new migration file for the model}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new Eloquent model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->addon = $this->getAddon();

        if (parent::fire() !== false) {
            if ($this->option('migration')) {
                $table = $this->toTable($this->argument('name'));

                $this->call('make:migration', [
                    'name' => $this->option('migration'),
                    '--addon' => $this->option('addon'),
                    '--create' => $table,
                ]);
            }
        }
    }

    /**
     * Convert name to table.
     *
     * @param string $name
     *
     * @return string
     */
    protected function toTable($name)
    {
        return Str::plural(Str::snake(class_basename($name)));
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
            'table' => $this->toTable($name),
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/model.stub';
    }

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
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }
}
