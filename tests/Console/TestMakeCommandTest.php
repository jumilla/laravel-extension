<?php

use LaravelPlus\Extension\Console\TestMakeCommand as Command;

class TestMakeCommandTest extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command);

            Assert::isFalse($result);
        } catch (RuntimeException $ex) {
            Assert::equals('Not enough arguments.', $ex->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_withNameParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $result = $this->runCommand($app, $command, [
                'name' => 'foo',
            ]);

            Assert::same(0, $result);
            Assert::same(true, is_file($app['path.base'].'/tests/foo.php'));
        } catch (RuntimeException $ex) {
            Assert::failed($ex->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_withNameAndAddonParameter_addonNotFound()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $result = $this->runCommand($app, $command, [
                'name' => 'foo',
                '--addon' => 'bar',
            ]);

            Assert::isFalse(true);
        }
        // RuntimeException: Addon 'bar' is not found.
        catch (RuntimeException $ex) {
            Assert::equals("Addon 'bar' is not found.", $ex->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_withNameAndAddonParameter_addonFound()
    {
        // 1. setup
        $app = $this->createApplication();
        $this->createAddon('bar', 'minimum', [
            'namespace' => 'Bar',
        ]);

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $result = $this->runCommand($app, $command, [
                'name' => 'foo',
                '--addon' => 'bar',
            ]);

            Assert::same(0, $result);
            Assert::same(true, is_file($app['path.base'].'/addons/bar/tests/foo.php'));
        } catch (RuntimeException $ex) {
            Assert::failed($ex->getMessage());
        }
    }
}
