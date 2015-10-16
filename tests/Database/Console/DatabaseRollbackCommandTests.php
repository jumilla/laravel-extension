<?php

use LaravelPlus\Extension\Database\Console\DatabaseRollbackCommand as Command;

class DatabaseRollbackCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition
        $migrator->shouldReceive('installedMigrationsByDesc')->andReturn(collect());

        // 3. test
        $migrator->shouldReceive('makeLogTable')->never();
        $migrator->shouldReceive('doDowngrade')->never();

        try {
            $this->runCommand($app, $command, []);
            Assert::failure();
        } catch (RuntimeException $ex) {
            Assert::success();
        }
    }
}
