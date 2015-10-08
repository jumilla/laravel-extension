<?php

use LaravelPlus\Extension\Addons\Console\AddonRemoveCommand as Command;

class AddonRemoveCommandTest extends TestCase
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
            $this->runCommand($app, $command);

            Assert::isFalse(true);
        } catch (RuntimeException $ex) {
            Assert::equals('Not enough arguments.', $ex->getMessage());
        }
    }
}
