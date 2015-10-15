<?php

use LaravelPlus\Extension\Addons\AddonGenerator;

class AddonGeneratorTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new AddonGenerator();

        Assert::isInstanceOf(AddonGenerator::class, $command);
    }
}
