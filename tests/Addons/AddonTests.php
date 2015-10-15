<?php

use LaravelPlus\Extension\Addons\Addon;

class AddonTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new Addon();

        Assert::isInstanceOf(Addon::class, $command);
    }
}
