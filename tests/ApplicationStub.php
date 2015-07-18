<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Support\Facades\Facade;

class ApplicationStub extends Container implements ApplicationContract
{
    /**
     * @param array $mocks
     */
    public function __construct(array $mocks = [])
    {
        $this->addMocks($mocks);

        Facade::setFacadeApplication($this);
    }

    /**
     * @param array $mocks
     * @return void
     */
    public function addMocks(array $mocks = [])
    {
        foreach ($mocks as $alias => $class) {
            if (is_string($alias)) {
                $this->instance($alias, Mockery::mock($class));
                $this->alias($alias, $class);
            } else {
                $this->instance($class, Mockery::mock($class));
            }
        }
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    public function basePath()
    {
        return __DIR__;
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     * @return string
     */
    public function environment()
    {
        return array_get($this, 'env', 'testing');
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return false;
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  array  $options
     * @param  bool   $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        return $provider;
    }

    /**
     * Register a deferred provider and service.
     *
     * @param  string  $provider
     * @param  string  $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null)
    {
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register a new boot listener.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function booting($callback)
    {
    }

    /**
     * Register a new "booted" listener.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function booted($callback)
    {
    }

    /**
     * Get the path to the cached "compiled.php" file.
     *
     * @return string
     */
    public function getCachedCompilePath()
    {
        return $this->basePath().'/cache';
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        return $this->basePath().'/cache';
    }
}
