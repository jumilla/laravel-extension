<?php

use LaravelPlus\Extension\Addons\Console\AddonMakeCommand as Command;

class AddonMakeCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withoutArguments()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = new Command();

        try {
            $result = $this->runCommand($app, $command);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::equals('Not enough arguments.', $ex->getMessage());
        }
    }

    public function test_withName_andType()
    {
        $app = $this->createApplication();

        $this->runMakeCommand($app, 'minimum');
        $this->runMakeCommand($app, 'simple');
        $this->runMakeCommand($app, 'library');
        $this->runMakeCommand($app, 'api');
        $this->runMakeCommand($app, 'ui');
        $this->runMakeCommand($app, 'debug');
        $this->runMakeCommand($app, 'laravel5');
        $this->runMakeCommand($app, 'sample:ui');
        $this->runMakeCommand($app, 'sample:auth');
    }

    public function runMakeCommand($app, $skeleton)
    {
        $command = $app->make(Command::class);

        return $this->runCommand($app, $command, [
            'name' => $skeleton,
            'skeleton' => $skeleton,
        ]);
    }
}
