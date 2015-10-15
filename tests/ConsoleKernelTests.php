<?php

use LaravelPlus\Extension\ConsoleKernel;

class ConsoleKernelTest extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new ConsoleKernelStub();

        Assert::isInstanceOf(ConsoleKernel::class, $command);
    }
}
