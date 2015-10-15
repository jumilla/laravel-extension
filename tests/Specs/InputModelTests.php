<?php

use LaravelPlus\Extension\Specs\InputModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\TranslatorInterface;

class InputModelTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock('spec');
        $app['translator'] = $translator = $this->createMock(TranslatorInterface::class);
        $app['request'] = new Request();
        $app['validator'] = new ValidatorFactory($translator, $app);

        $spec->shouldReceive('get')->with('foo')->andReturn([])->once();
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();
        $translator->shouldReceive('get')->with('foo.rules')->andReturn([])->once();
        $translator->shouldReceive('get')->with('foo.attributes')->andReturn([])->once();

        $command = new InputModel('foo');

        Assert::isInstanceOf(InputModel::class, $command);
    }
}
