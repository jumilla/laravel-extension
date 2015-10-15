<?php

use LaravelPlus\Extension\ServiceProvider;

class ServiceProviderTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new ServiceProvider();

        Assert::isInstanceOf(ServiceProvider::class, $command);
    }
}
