<?php

namespace LaravelPlus\Extension\Generators\Console;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class MiddlewareMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:middleware
        {name : The name of the class}
        {--a|addon= : The name of the addon}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new middleware class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Middleware';

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
        return $this->getRootNamespace().'\\Http\\Middleware';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return 'middleware.stub';
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
        ]);
    }
}
