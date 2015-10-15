<?php

use LaravelPlus\Extension\Database\Console\DatabaseSeedCommand as Command;

class DatabaseSeedCommandTests extends TestCase
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
