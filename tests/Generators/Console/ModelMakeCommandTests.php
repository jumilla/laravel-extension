<?php

use LaravelPlus\Extension\Generators\Console\ModelMakeCommand as Command;

class ModelMakeCommandTests extends TestCase
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

            Assert::failure();
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

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path'].'/Foo.php');
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
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path.base'].'/addons/bar/classes/Foo.php');
    }

    /**
     * @test
     */
    public function test_withNameAndAddonParameter_addonFound_doMigrate()
    {
        // 1. setup
        $app = $this->createApplication();
        $command = $this->createMock(Command::class, [
            'call',
        ]);

        // 2. condition
        $this->createAddon('bar', 'minimum', [
            'namespace' => 'Bar',
        ]);

        // 3. test
        $command->shouldReceive('call')->with('make:migration', ['name' => 'baz', '--addon' => 'bar', '--create' => 'foos'])->once()->andReturn(0);

        $result = $this->runCommand($app, $command, [
            'name' => 'foo',
            '--addon' => 'bar',
            '--migration' => 'baz',
        ]);

        Assert::same(0, $result);
        Assert::fileExists($app['path.base'].'/addons/bar/classes/Foo.php');
    }
}
