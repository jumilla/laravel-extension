<?php

use LaravelPlus\Extension\Addons\Console\AddonMakeCommand as Command;

class AddonMakeCommandTest extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();

        // 2. condition

        // 3. test
        $command = new Command();

        try {
            $result = $this->runCommand($app, $command);

            Assert::isTrue($result == 0);
        } catch (RuntimeException $ex) {
            Assert::equals('Not enough arguments.', $ex->getMessage());
        }
    }
}
