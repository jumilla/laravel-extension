<?php

use LaravelPlus\Extension\HttpKernel;

class HttpKernelTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new HttpKernelStub();

        Assert::isInstanceOf(HttpKernel::class, $command);
    }
}
