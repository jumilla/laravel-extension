<?php

use LaravelPlus\Extension\Database\Console\DatabaseCleanCommand as Command;

class DatabaseCleanCommandTests extends TestCase
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
