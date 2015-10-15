<?php

use LaravelPlus\Extension\Database\Console\DatabaseRefreshCommand as Command;

class DatabaseRefreshCommandTest extends TestCase
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
