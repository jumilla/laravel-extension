<?php

use LaravelPlus\Extension\Specs\FormModel;
use LaravelPlus\Extension\Repository\NamespacedRepository;
use Illuminate\Translation\Translator;

class SpecsFormModelTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $app['specs'] = $spec = $this->createMock(NamespacedRepository::class);
        $app['translator'] = $translator = $this->createMock(Translator::class);

        $spec->shouldReceive('get')->with('bar')->andReturn(['qux' => 'quux'])->once();
        $translator->shouldReceive('has')->with('bar')->andReturn(true)->once();

        $formModel = FormModel::make('foo', 'bar');

        Assert::same('foo', $formModel->id());
        Assert::same('foo-baz', $formModel->fieldId('baz'));
        Assert::same(['qux'], $formModel->attributes());
    }
}
