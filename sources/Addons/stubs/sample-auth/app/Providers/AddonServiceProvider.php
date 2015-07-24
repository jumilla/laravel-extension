<?php

namespace {$namespace}\Providers;

class AddonServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        View::addLocation(realpath(addon_path(addon_name(), 'resources/views')));

        $this->setupPublishFiles();
    }

    /**
     * Setup publish files.
     *
     * @return void
     */
    protected function setupPublishFiles()
    {
        $this->publishes([
            addon_path(addon_name(), 'public') => base_path('public'),
        ]);
    }
}
