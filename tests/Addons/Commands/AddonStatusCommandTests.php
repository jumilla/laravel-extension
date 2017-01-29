<?php

use LaravelPlus\Extension\Addons\Commands\AddonStatusCommand as Command;

class AddonStatusCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();
        $migrator = $this->createMigrator();
        $command = new Command();

        // 2. condition

        // 3. test
        $this->runCommand($app, $command, []);
        Assert::success();
    }
}
