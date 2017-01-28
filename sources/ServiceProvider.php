<?php

namespace LaravelPlus\Extension;

use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;

class ServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        Providers\ArtisanServiceProvider::class,
        ComposerServiceProvider::class,
        Providers\ExtensionServiceProvider::class,
    ];
}
