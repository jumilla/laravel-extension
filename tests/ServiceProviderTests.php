<?php

use LaravelPlus\Extension\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ServiceProviderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $blade = $this->createMock('alias:'.Blade::class);
        $blade->shouldReceive('extend')->atLeast()->times(1);

        $app['config']->set('app.aliases', []);

        $command = new ServiceProvider($app);
        $command->register();
        $command->boot();
    }
}
