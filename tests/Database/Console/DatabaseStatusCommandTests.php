<?php

use LaravelPlus\Extension\Database\Console\DatabaseStatusCommand as Command;

class DatabaseStatusCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_run()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition
        $migrator->shouldReceive('migrationGroups')->andReturn([]);
        $migrator->shouldReceive('seedNames')->andReturn([]);

        // 3. test
        $migrator->shouldReceive('makeLogTable')->once();

        $this->runCommand($app, $command, []);
    }
}
