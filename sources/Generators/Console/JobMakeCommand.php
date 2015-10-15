<?php

namespace LaravelPlus\Extension\Generators\Console;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class JobMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
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
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Job';

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
        return $this->getRootNamespace().'\\Jobs';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('queued') ? 'job-queued.stub' : 'job.stub';
    }

    /**
     * Generate file.
     *
     * @param FileGenerator $generator
     * @param string        $path
     * @param string        $fqcn
     *
     * @return bool
     */
    protected function generateFile(FileGenerator $generator, $path, $fqcn)
    {
        list($namespace, $class) = $this->splitFullQualifyClassName($fqcn);

        return $generator->file($path)->template($this->getStub(), [
            'namespace' => $namespace,
            'root_namespace' => $this->getAppNamespace(),     // use App\Jobs\Job
            'class' => $class,
        ]);
    }
}
