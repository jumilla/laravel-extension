<?php

use LaravelPlus\Extension\Specs\FormModel;

class FormModelTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock('spec');
        $app['translator'] = $translator = $this->createMock('translator');

        $spec->shouldReceive('get')->with('bar')->andReturn([])->once();
        $translator->shouldReceive('has')->with('bar')->andReturn(true)->once();

        $command = new FormModel('foo', 'bar');

        Assert::isInstanceOf(FormModel::class, $command);
    }
}
