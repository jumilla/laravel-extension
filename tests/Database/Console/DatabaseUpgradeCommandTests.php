<?php

use LaravelPlus\Extension\Database\Commands\DatabaseUpgradeCommand as Command;

class DatabaseUpgradeCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_whenMigrationNotDefined()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator([
            'installedLatestMigrations',
            'makeLogTable',
        ]);
        $command = new Command();

        // 2. condition
        $migrator->shouldReceive('installedLatestMigrations')->andReturn(collect());

        // 3. test
        $migrator->shouldReceive('makeLogTable')->once();

        $this->runCommand($app, $command, []);
    }
}
