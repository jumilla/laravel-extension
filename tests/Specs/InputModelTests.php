<?php

use LaravelPlus\Extension\Specs\InputModel;

class InputModelTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new InputModel();

        Assert::isInstanceOf(InputModel::class, $command);
    }
}
