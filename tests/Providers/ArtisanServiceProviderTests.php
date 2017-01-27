<?php

use LaravelPlus\Extension\Providers\ArtisanServiceProvider;

class ArtisanServiceProviderTests extends TestCase
{
    public function test_canInstanciate()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);

        Assert::isTrue(true);
    }
}
