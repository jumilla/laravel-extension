<?php

use LaravelPlus\Extension\Specs\Factory;
use LaravelPlus\Extension\Specs\InputSpec;
use LaravelPlus\Extension\Specs\Translator;
use LaravelPlus\Extension\Specs\InputModel;
use LaravelPlus\Extension\Specs\FormModel;
use LaravelPlus\Extension\Repository\NamespacedRepository;
use Illuminate\Translation\Translator as LaravelTranslator;
use Illuminate\Validation\Factory as ValidatorFactory;

class FactoryTests extends TestCase
{
    public function test_makeInputSpec()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);

        $spec->shouldReceive('get')->with('foo')->andReturn([])->once();
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();

        $factory = new Factory;

        Assert::isInstanceOf(InputSpec::class, $factory->make('foo'));
    }

    public function test_getSpec()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);

        $spec->shouldReceive('get')->with('foo', null)->andReturn('bar')->once();

        $factory = new Factory;

        Assert::same('bar', $factory->get('foo'));
    }

    public function test_getSpec_withDefault()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);

        $spec->shouldReceive('get')->with('foo', 'bar')->andReturn('bar')->once();

        $factory = new Factory;

        Assert::same('bar', $factory->get('foo', 'bar'));
    }

    public function test_makeTranslator()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);

        $factory = new Factory;

        Assert::isInstanceOf(Translator::class, $factory->translator('foo'));
    }

    public function test_makeInputModel()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);
        $app['validator'] = new ValidatorFactory($translator, $app);

        $spec->shouldReceive('get')->with('foo')->andReturn([]);
        $translator->shouldReceive('has')->with('foo')->andReturn(true);
        $translator->shouldReceive('get')->with('foo.rules')->andReturn([]);
        $translator->shouldReceive('get')->with('foo.attributes')->andReturn([]);

        $factory = new Factory;

        Assert::isInstanceOf(InputModel::class, $factory->inputModel('foo', ['bar']));
        Assert::isInstanceOf(InputModel::class, $factory->inputModel($factory->make('foo'), ['bar']));
    }

    public function test_makeFormModel()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(LaravelTranslator::class);

        $spec->shouldReceive('get')->andReturn([])->once();
        $translator->shouldReceive('has')->with('bar')->andReturn(true);

        $factory = new Factory;

        Assert::isInstanceOf(FormModel::class, $factory->formModel('foo', 'bar'));
    }
}
