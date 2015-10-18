<?php

use LaravelPlus\Extension\Generators\GeneratorCommandTrait;

class GeneratorCommandTraitTests extends TestCase
{
    public function test_instantiate()
    {
        $instance = new GeneratorCommandStub();

        Assert::notNull($instance);
    }

    public function test_handleMethodFailed_becauseProcessArgumentsReturnFalse()
    {
        $instance = new GeneratorCommandStub();

        Assert::false($instance->handle());
    }
}

class GeneratorCommandStub
{
    use GeneratorCommandTrait;

    public function processArguments()
    {
        return false;
    }
}
