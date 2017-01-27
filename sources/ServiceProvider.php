<?php

namespace LaravelPlus\Extension;

use Illuminate\Support\AggregateServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        Providers\ArtisanServiceProvider::class,
        Providers\ExtensionServiceProvider::class,
    ];
}
