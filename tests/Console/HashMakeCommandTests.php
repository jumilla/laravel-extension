<?php

use LaravelPlus\Extension\Console\HashMakeCommand as Command;
use Illuminate\Hashing\BcryptHasher;

class HashMakeCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withoutArguments()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withString()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string' => 'foo',
        ]);

        Assert::same(0, $result);
    }

    public function test_withString_andCost()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string' => 'foo',
            '--cost' => '12',
        ]);

        Assert::same(0, $result);
    }
}
