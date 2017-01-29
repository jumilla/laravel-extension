<?php

use LaravelPlus\Extension\Database\Commands\DatabaseCleanCommand as Command;

class DatabaseCleanCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_whenMigrationNotInstalled()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition
        $migrator->shouldReceive('installedMigrationsByDesc')->andReturn(collect());

        // 3. test
        $migrator->shouldReceive('makeLogTable')->once();
        $migrator->shouldReceive('doDowngrade')->never();

        $this->runCommand($app, $command, []);
    }
}
