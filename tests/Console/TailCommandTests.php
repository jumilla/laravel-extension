<?php

use LaravelPlus\Extension\Console\TailCommand as Command;

class TailCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_run()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }

    public function test_run_withPathOption()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            '--path' => 'bar',
        ]);

        Assert::same(0, $result);
    }

    public function test_run_logConfigIsSyslog()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition
        $app['config']->set('app.log', 'syslog');

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }

    public function test_run_withConnectArgument()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'connection' => 'foo',
        ]);

        Assert::same(0, $result);
    }

    public function test_run_withConnectArgument_andPathOption()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'connection' => 'foo',
            '--path' => 'bar',
        ]);

        Assert::same(0, $result);
    }
}
