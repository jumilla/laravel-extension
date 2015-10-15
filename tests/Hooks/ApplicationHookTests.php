<?php

use LaravelPlus\Extension\Hooks\ApplicationHook;

class ApplicationHookTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new ApplicationHook();

        Assert::isInstanceOf(ApplicationHook::class, $command);
    }
}
