<?php

use LaravelPlus\Extension\Specs\InputSpec;

class InputSpecTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new InputSpec();

        Assert::isInstanceOf(InputSpec::class, $command);
    }
}
