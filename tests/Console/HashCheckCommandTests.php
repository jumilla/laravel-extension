<?php

use LaravelPlus\Extension\Console\HashCheckCommand as Command;
use Illuminate\Hashing\BcryptHasher;

class HashCkeckCommandTests extends TestCase
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

    public function test_withString1()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command, [
                'string1' => 'foo',
            ]);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withString2()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command, [
                'string2' => 'foo',
            ]);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::stringStartsWith('Not enough arguments', $ex->getMessage());
        }
    }

    public function test_withString_andEncryptedString()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = $hash = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string1' => 'foo',
            'string2' => $hash->make('foo'),
        ]);

        Assert::same(0, $result);
    }

    public function test_withEncryptedString_andString()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = $hash = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string1' => $hash->make('foo'),
            'string2' => 'foo',
        ]);

        Assert::same(0, $result);
    }

    public function test_withEncryptedString_andString_different()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = $hash = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string1' => $hash->make('foo'),
            'string2' => 'bar',
        ]);

        Assert::same(0, $result);
    }

    public function test_withString1_andString2_andCost()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['hash'] = $hash = new BcryptHasher();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'string1' => 'foo',
            'string2' => 'bar',
            '--cost' => 12,
        ]);

        Assert::same(0, $result);
    }
}
