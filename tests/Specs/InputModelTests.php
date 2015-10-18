<?php

use LaravelPlus\Extension\Specs\InputModel;
use LaravelPlus\Extension\Specs\InputSpec;
use LaravelPlus\Extension\Repository\NamespacedRepository;
use Symfony\Component\Translation\TranslatorInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

class InputModelTests extends TestCase
{
    public function test_makeMethod()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(TranslatorInterface::class);
        $app['validator'] = new ValidatorFactory($translator, $app);
        $app['request'] = $request = new Request();

        $spec->shouldReceive('get')->with('foo')->andReturn([])->once();
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();
        $translator->shouldReceive('get')->with('foo.rules')->andReturn([])->once();
        $translator->shouldReceive('get')->with('foo.attributes')->andReturn([])->once();

        $inputModel = InputModel::make('foo', []);

        Assert::isInstanceOf(InputModel::class, $inputModel);
    }

    public function test_gatherInputMethod_andPassesMethod()
    {
        $app = $this->createApplication();
        $spec = $this->createMock(InputSpec::class);
        $app['translator'] = $translator = $this->createMock(TranslatorInterface::class);
        $app['validator'] = new ValidatorFactory($translator, $app);
        $app['request'] = $request = $this->createMock(Request::class);

        $spec->shouldReceive('rules')->andReturn(['foo' => 'required'])->once();
        $spec->shouldReceive('ruleMessages')->andReturn([])->once();
        $spec->shouldReceive('labels')->andReturn([])->once();
        $spec->shouldReceive('attributes')->andReturn(['foo'])->once();
        $request->shouldReceive('only')->with(['foo'])->andReturn(['foo' => 'bar'])->once();

        $inputModel = new InputModel($spec);

        Assert::true($inputModel->passes());
    }

    public function test_failsMethod()
    {
        $app = $this->createApplication();
        $spec = $this->createMock(InputSpec::class);
        $app['translator'] = $translator = $this->createMock(TranslatorInterface::class);
        $app['validator'] = $validator = new ValidatorFactory($translator, $app);

        $spec->shouldReceive('rules')->andReturn(['qux' => 'required'])->once();
        $spec->shouldReceive('ruleMessages')->andReturn([])->once();
        $spec->shouldReceive('labels')->andReturn([])->once();
        $translator->shouldReceive('trans')->andReturn('failed');

        $inputModel = new InputModel($spec, ['qux' => '']);

        Assert::true($inputModel->fails());
        Assert::same(['failed'], $inputModel->errors()->all());
    }

    public function test_otherMethods()
    {
        $app = $this->createApplication();
        $spec = $this->createMock(InputSpec::class);
        $app['translator'] = $translator = $this->createMock(TranslatorInterface::class);
        $app['validator'] = $validator = new ValidatorFactory($translator, $app);

        $spec->shouldReceive('rules')->andReturn(['qux' => 'required'])->once();
        $spec->shouldReceive('ruleMessages')->andReturn([])->once();
        $spec->shouldReceive('labels')->andReturn([])->once();
        $translator->shouldReceive('trans')->andReturn('failed');

        $inputModel = new InputModel($spec, ['qux' => '']);
        $inputModel->foo = 'bar';

        Assert::isInstanceOf(Validator::class, $inputModel->validator());
        Assert::same(['qux' => '', 'foo' => 'bar'], $inputModel->input());
        Assert::same('bar', $inputModel->foo);
        Assert::same(['qux' => '', 'foo' => 'bar'], $inputModel->toArray());
    }
}
