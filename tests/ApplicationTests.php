<?php

use LaravelPlus\Extension\Application;

class ApplicationTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new Application();

        Assert::isInstanceOf(Application::class, $command);
    }
}
