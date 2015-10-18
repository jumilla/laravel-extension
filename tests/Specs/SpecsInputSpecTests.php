<?php

use LaravelPlus\Extension\Specs\InputSpec;
use LaravelPlus\Extension\Repository\NamespacedRepository;
use Illuminate\Translation\Translator;

class SpecsInputSpecTests extends TestCase
{
    public function test_methods()
    {
        $app = $this->createApplication();
        $spec = $this->createMock(NamespacedRepository::class);
        $translator = $this->createMock(Translator::class);

        $spec->shouldReceive('get')->with('foo')->andReturn(['attr1' => 'required', 'attr2' => 'string|url'])->once();
        $translator->shouldReceive('has')->with('foo')->andReturn(true)->once();
        $translator->shouldReceive('get')->with('foo.rules')->andReturn(['foo_rules']);
        $translator->shouldReceive('get')->with('foo.attributes')->andReturn(['foo_attributes']);
        $translator->shouldReceive('get')->with('foo.attributes.attr1')->andReturn('Attribute1');
        $translator->shouldReceive('get')->with('foo.helptexts.attr1')->andReturn('Help of Attribute1');
        $translator->shouldReceive('get')->with('foo.values')->andReturn(['foo_values']);

        $command = new InputSpec($spec, $translator, 'foo');

        Assert::isInstanceOf(InputSpec::class, $command);
        Assert::same(['attr1', 'attr2'], $command->attributes());
        Assert::same(['attr1' => 'required', 'attr2' => 'string|url'], $command->rules());
        Assert::same(['foo_rules'], $command->ruleMessages());
        Assert::same(['foo_attributes'], $command->labels());
        Assert::same(['foo_values'], $command->values());
        Assert::true($command->required('attr1'));
        Assert::false($command->required('attr2'));
        Assert::same('Attribute1', $command->label('attr1'));
        Assert::same('Help of Attribute1', $command->helptext('attr1'));
    }
}
