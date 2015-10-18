<?php

use LaravelPlus\Extension\Generators\Console\DummyCommand as Command;

class DummyCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_run()
    {
        $app = $this->createApplication();

        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }
}
