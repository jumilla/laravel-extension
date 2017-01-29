<?php

namespace LaravelPlus\Extension\Generators\Commands;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;
use Illuminate\Support\Str;

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
        {--a|addon= : The name of the addon}
        {--m|migration= : Create a new migration file for the model}
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
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStubDirectory(__DIR__.'/../stubs');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->addon = $this->getAddon();

        if (parent::handle() !== false) {
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
     * Get the default namespace for the class.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return $this->getRootNamespace();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return 'model.stub';
    }

    /**
     * Generate file.
     *
     * @param \Jumilla\Generators\FileGenerator $generator
     * @param string $path
     * @param string $fqcn
     *
     * @return bool
     */
    protected function generateFile(FileGenerator $generator, $path, $fqcn)
    {
        list($namespace, $class) = $this->splitFullQualifyClassName($fqcn);

        return $generator->file($path)->template($this->getStub(), [
            'namespace' => $namespace,
            'class' => $class,
            'table' => $this->toTable($class),
        ]);
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
}
