<?php

use LaravelPlus\Extension\Console\AppContainerCommand as Command;

class AppContainerCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_run()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition
        // TODO
        $app->singleton('foo', 'string');

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }
}
