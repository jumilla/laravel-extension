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

        $this->injectCustomStubs();
    }

    /**
     * Inject custom stub files.
     *
     * @return void
     */
    protected function injectCustomStubs()
    {
        $files = $this->app['files'];
        $stub_directory = addon()->config('commands.stub.directory');

        $routine = function ($instance, $app) use ($stub_directory) {
            $instance->setStubDirectory($stub_directory);

            return $instance;
        };

        if (addon()->config('commands.stub.overrides')) {
            if ($files->exists($stub_directory.'/console.stub')) {
                $this->app->extend('command+.console.make', $routine);
            }
            if ($files->exists($stub_directory.'/controller.stub')) {
                $this->app->extend('command+.controller.make', $routine);
            }
            if ($files->exists($stub_directory.'/event.stub')) {
                $this->app->extend('command+.event.make', $routine);
            }
            if ($files->exists($stub_directory.'/job.stub')) {
                $this->app->extend('command+.job.make', $routine);
            }
            if ($files->exists($stub_directory.'/listener.stub')) {
                $this->app->extend('command+.listener.make', $routine);
            }
            if ($files->exists($stub_directory.'/middleware.stub')) {
                $this->app->extend('command+.middleware.make', $routine);
            }
            if ($files->exists($stub_directory.'/migration.stub')) {
                $this->app->extend('command+.migration.make', $routine);
            }
            if ($files->exists($stub_directory.'/model.stub')) {
                $this->app->extend('command+.model.make', $routine);
            }
            if ($files->exists($stub_directory.'/policy.stub')) {
                $this->app->extend('command+.policy.make', $routine);
            }
            if ($files->exists($stub_directory.'/provider.stub')) {
                $this->app->extend('command+.provider.make', $routine);
            }
            if ($files->exists($stub_directory.'/request.stub')) {
                $this->app->extend('command+.request.make', $routine);
            }
            if ($files->exists($stub_directory.'/seeder.stub')) {
                $this->app->extend('command+.seeder.make', $routine);
            }
            if ($files->exists($stub_directory.'/test.stub')) {
                $this->app->extend('command+.test.make', $routine);
            }
        }
    }
}
