<?php

use LaravelPlus\Extension\ServiceProvider;

class ServiceProviderTests extends TestCase
{
    public function test_canInstanciate()
    {
        $app = $this->createApplication();

        $provider = new ServiceProvider($app);

        Assert::isTrue(true);
    }
}
