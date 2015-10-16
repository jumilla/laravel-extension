<?php

use LaravelPlus\Extension\Database\Console\DatabaseSeedCommand as Command;

class DatabaseSeedCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_whenSeedNotDefined()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition

        // 3. test
        $migrator->shouldReceive('defaultSeed')->once()->andReturn('');
        $migrator->shouldReceive('seedClass')->never();
        $migrator->shouldReceive('installedMigrationsByDesc')->never();

        $this->runCommand($app, $command, []);
    }
}
