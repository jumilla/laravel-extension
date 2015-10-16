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
        if (!$this->app->isLocal()) {
            return;
        }

        $files = $this->app['files'];
        $stub_directory = addon_config(addon_name(), 'commands.stub.directory');

        $routine = function ($instance, $app) use ($stub_directory) {
            $instance->setStubDirectory($stub_directory);
            return $instance;
        };

        if (addon_config(addon_name(), 'commands.stub.overrides')) {
            if ($files->exists('console.stub')) {
                $this->app->extend('command.console.make', $routine);
            }
            if ($files->exists('controller.stub')) {
                $this->app->extend('command.controller.make', $routine);
            }
            if ($files->exists('event.stub')) {
                $this->app->extend('command.event.make', $routine);
            }
            if ($files->exists('job.stub')) {
                $this->app->extend('command.job.make', $routine);
            }
            if ($files->exists('listener.stub')) {
                $this->app->extend('command.listener.make', $routine);
            }
            if ($files->exists('middleware.stub')) {
                $this->app->extend('command.middleware.make', $routine);
            }
            if ($files->exists('migration.stub')) {
                $this->app->extend('command.migration.make', $routine);
            }
            if ($files->exists('model.stub')) {
                $this->app->extend('command.model.make', $routine);
            }
            if ($files->exists('policy.stub')) {
                $this->app->extend('command.policy.make', $routine);
            }
            if ($files->exists('provider.stub')) {
                $this->app->extend('command.provider.make', $routine);
            }
            if ($files->exists('request.stub')) {
                $this->app->extend('command.request.make', $routine);
            }
            if ($files->exists('seeder.stub')) {
                $this->app->extend('command.seeder.make', $routine);
            }
            if ($files->exists('test.stub')) {
                $this->app->extend('command.test.make', $routine);
            }
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
}
