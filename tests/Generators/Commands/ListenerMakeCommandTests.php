<?php

use LaravelPlus\Extension\Generators\Commands\ListenerMakeCommand as Command;

class ListenerMakeCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_noParameter()
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

    /**
     * @test
     */
    public function test_withNameParameter_noEvent()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        try {
            $this->runCommand($app, $command, [
                'name' => 'foo',
            ]);

            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::equals('Missing required option: --event', $ex->getMessage());
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

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
            '--event' => 'bar',
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path'].'/Listeners/Foo.php');
    }

    /**
     * @test
     */
    public function test_withNameParameter_withQueued()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
            '--event' => 'bar',
            '--queued' => true,
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path'].'/Listeners/Foo.php');
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
                '--event' => 'baz',
            ]);

            Assert::failure();
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

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
            '--addon' => 'bar',
            '--event' => 'baz',
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path.base'].'/addons/bar/classes/Listeners/Foo.php');
    }

    /**
     * @test
     */
    public function test_withNameAndAddonParameter_withQueued()
    {
        // 1. setup
        $app = $this->createApplication();
        $this->createAddon('bar', 'minimum', [
            'namespace' => 'Bar',
        ]);

        // 2. condition

        // 3. test
        $command = $app->make(Command::class);

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
            '--addon' => 'bar',
            '--event' => 'baz',
            '--queued' => true,
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path.base'].'/addons/bar/classes/Listeners/Foo.php');
    }
}
