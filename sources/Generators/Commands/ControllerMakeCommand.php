<?php

namespace LaravelPlus\Extension\Generators\Commands;

use Jumilla\Generators\Laravel\OneFileGeneratorCommand as BaseCommand;
use Jumilla\Generators\FileGenerator;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Generators\GeneratorCommandTrait;
use InvalidArgumentException;

class ControllerMakeCommand extends BaseCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command singature.
     *
     * @var string
     */
    protected $signature = 'make:controller
        {name : The name of the class}
        {--a|addon= : The name of the addon}
        {--m|model= : Generate a resource controller for the given model}
        {--r|resource : Generate a resource controller class}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

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
        return $this->getRootNamespace().'\\'.($this->onAddon() ? 'Controllers' : 'Http\\Controllers');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return 'controller-model.stub';
        }
        return $this->option('resource') ? 'controller-resource.stub' : 'controller-plain.stub';
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
        list($model_namespace, $model_class) = $this->splitFullQualifyClassName($this->parseModel($this->option('model', '')));

        return $generator->file($path)->template($this->getStub(), [
            'namespace' => $namespace,
            'root_namespace' => $this->getAppNamespace(),   // use App\Http\Controllers\Controller
            'class' => $class,
            'model_namespace' => $model_namespace ? $model_namespace.'\\' : '',
            'model_class' => $model_class,
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        return $model;
    }
}
