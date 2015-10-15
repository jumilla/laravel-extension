<?php

use LaravelPlus\Extension\Specs\InputSpec;

class InputSpecTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock('spec');
        $app['translator'] = $translator = $this->createMock('translator');

        $spec->shouldReceive('get')->with('foo')->andReturn([])->once();
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();

        $command = new InputSpec('foo');

        Assert::isInstanceOf(InputSpec::class, $command);
    }
}
