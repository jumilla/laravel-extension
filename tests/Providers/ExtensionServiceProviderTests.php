<?php

use LaravelPlus\Extension\Providers\ExtensionServiceProvider;
use Jumilla\Addomnipot\Laravel\Events;
use Illuminate\Support\Facades\Blade;

class ExtensionServiceProviderTests extends TestCase
{
    public function test_addonEvents()
    {
        $app = $this->createApplication();
        $blade = $this->createMock('alias:'.Blade::class);
        $blade->shouldReceive('extend');

        $created = 0;
        $registered = 0;
        $booted = 0;
        $app['events']->listen(Events\AddonWorldCreated::class, function ($env) use (&$created) {
            ++$created;
        });
        $app['events']->listen(Events\AddonRegistered::class, function ($env) use (&$registered) {
            ++$registered;
        });
        $app['events']->listen(Events\AddonBooted::class, function ($env) use (&$booted) {
            ++$booted;
        });

        $provider = new ExtensionServiceProvider($app);
        $provider->register();
        $provider->boot();

        Assert::same(1, $created);
        Assert::same(1, $registered);
        Assert::same(1, $booted);
    }

    public function test_bladeExtensionRegistered()
    {
        $app = $this->createApplication();
        $blade = $this->createMock('alias:'.Blade::class);
        $blade->shouldReceive('extend')->times(2);

        $provider = new ExtensionServiceProvider($app);
        $provider->register();
        $provider->boot();
    }
}
