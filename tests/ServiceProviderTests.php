<?php

use LaravelPlus\Extension\ServiceProvider;

class ServiceProviderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $command = new ServiceProvider($app);

        Assert::isInstanceOf(ServiceProvider::class, $command);
    }
}
