<?php

use LaravelPlus\Extension\Specs\FormModel;

class FormModeTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new FormModel();

        Assert::isInstanceOf(FormModel::class, $command);
    }
}
