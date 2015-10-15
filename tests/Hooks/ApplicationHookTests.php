<?php

use LaravelPlus\Extension\Hooks\ApplicationHook;
use Illuminate\Foundation\Application;

class ApplicationHookTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createMock(Application::class);
        $hook = new ApplicationHook($app);

        Assert::isInstanceOf(Application::class, $app);
        Assert::isInstanceOf(ApplicationHook::class, $hook);
    }
}
