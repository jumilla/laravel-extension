<?php

namespace LaravelPlus\Extension\Generators\Console;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class MailMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:mail
        {name : The name of the class}
        {--a|addon= : The name of the addon}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new email class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mail';

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
        return $this->getRootNamespace().'\\Mail';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return 'mail.stub';
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
