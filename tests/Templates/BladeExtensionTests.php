<?php

use LaravelPlus\Extension\Templates\BladeExtension;

class BladeExtensionTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new BladeExtension();

        Assert::isInstanceOf(BladeExtension::class, $command);
    }
}
