<?php

use LaravelPlus\Extension\Database\Console\DatabaseUpgradeCommand as Command;

class DatabaseUpgradeCommandTest extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new Command();

        Assert::isInstanceOf(Command::class, $command);
    }
}
