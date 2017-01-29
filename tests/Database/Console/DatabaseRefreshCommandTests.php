<?php

use LaravelPlus\Extension\Database\Commands\DatabaseRefreshCommand as Command;

class DatabaseRefreshCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_whenNoDefinition()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition
        $migrator->shouldReceive('migrationGroups')->andReturn([]);
        $migrator->shouldReceive('installedMigrationsByDesc')->andReturn(collect());

        // 3. test
        $migrator->shouldReceive('makeLogTable')->once();

        $this->runCommand($app, $command, []);
    }
}
