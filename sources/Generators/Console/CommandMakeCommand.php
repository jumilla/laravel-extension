<?php

namespace LaravelPlus\Extension\Generators\Console;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class CommandMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:command
        {name : The name of the class}
        {--addon= : The name of the addon}
        {--command=command.name : The terminal command that should be assigned}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new Artisan command';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Console';

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
        return $this->getRootNamespace().'\\'.($this->onAddon() ? 'Commands' : 'Console\\Commands');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return 'command.stub';
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
            'command' => $this->option('command'),
        ]);
    }
}
