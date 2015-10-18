<?php

use LaravelPlus\Extension\Specs\Translator;
use Illuminate\Translation\Translator as LaravelTranslator;

class TranslatorTests extends TestCase
{
    public function test_methods_namespaceless()
    {
        $app = $this->createApplication();
        $translator = $this->createMock(LaravelTranslator::class);
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();
        $translator->shouldReceive('get')->with('foo')->andReturn('bar')->once();
        $translator->shouldReceive('get')->with('baz')->andReturn(['qux', 'quux'])->once();

        $instance = new Translator($translator);

        Assert::true($instance->has('foo'));
        Assert::same('bar', $instance->get('foo'));
        Assert::same(['qux', 'quux'], $instance->get('baz'));
    }

    public function test_methods_namespaced()
    {
        $app = $this->createApplication();
        $translator = $this->createMock(LaravelTranslator::class);
        $translator->shouldReceive('has')->with('ns::foo')->andReturn(true)->once();
        $translator->shouldReceive('get')->with('ns::foo')->andReturn('bar')->once();
        $translator->shouldReceive('get')->with('ns::baz')->andReturn(['qux', 'quux'])->once();

        $instance = new Translator($translator, 'ns');

        Assert::true($instance->has('foo'));
        Assert::same('bar', $instance->get('foo'));
        Assert::same(['qux', 'quux'], $instance->get('baz'));
    }
}
