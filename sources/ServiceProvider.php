<?php

namespace LaravelPlus\Extension;

use Illuminate\Support\Facades\Blade;
use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;
use Jumilla\Addomnipot\Laravel\ClassLoader as AddonClassLoader;
use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;
use Jumilla\Addomnipot\Laravel\AliasResolver;
use Jumilla\Addomnipot\Laravel\Repository;
use LaravelPlus\Extension\Templates\BladeExtension;
use Jumilla\Versionia\Laravel\Migrator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Addon environment.
     *
     * @var \Jumilla\Addomnipot\Laravel\Environment
     */
    protected $addonEnvironment;

    /**
     * @var array
     */
    protected static $commands = [
// app:
        'command+.app.container' => Console\AppContainerCommand::class,
        'command+.app.route' => Console\RouteListCommand::class,
        'command+.app.tail' => Console\TailCommand::class,
// addon:
        'command+.addon.list' => Addons\Console\AddonListCommand::class,
        'command+.addon.status' => Addons\Console\AddonStatusCommand::class,
        'command+.addon.make' => Addons\Console\AddonMakeCommand::class,
        'command+.addon.name' => Addons\Console\AddonNameCommand::class,
        'command+.addon.remove' => Addons\Console\AddonRemoveCommand::class,
// database:
        'command+.database.status' => Database\Console\DatabaseStatusCommand::class,
        'command+.database.upgrade' => Database\Console\DatabaseUpgradeCommand::class,
        'command+.database.clean' => Database\Console\DatabaseCleanCommand::class,
        'command+.database.refresh' => Database\Console\DatabaseRefreshCommand::class,
        'command+.database.rollback' => Database\Console\DatabaseRollbackCommand::class,
        'command+.database.again' => Database\Console\DatabaseAgainCommand::class,
        'command+.database.seed' => Database\Console\DatabaseSeedCommand::class,
// hash:
        'command+.hash.make' => Console\HashMakeCommand::class,
        'command+.hash.check' => Console\HashCheckCommand::class,
    ];

    /**
     * @var array
     */
    protected $addons;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $app = $this->app;

        // register spec path for app
        $app['path.specs'] = $app->basePath().'/resources/specs';

        // register spec repository
        $app->singleton('specs', function ($app) {
            $loader = new Repository\FileLoader($app['files'], $app['path.specs']);

            return new Repository\NamespacedRepository($loader);
        });

        // register addon environment
        $app->instance('addon', $this->addonEnvironment = new AddonEnvironment($app));
        $app->alias('addon', AddonEnvironment::class);

        // register addon generator
        $app->singleton('addon.generator', function ($app) {
            return new AddonGenerator();
        });
        $app->alias('addon.generator', AddonGenerator::class);

        // register database migrator
        $app->singleton('database.migrator', function ($app) {
            return new Migrator($app['db'], $app['config']);
        });
        $app->alias('database.migrator', Migrator::class);

        $this->registerClassResolvers();

        $this->setupPackageCommands(static::$commands);

        $this->registerAddons($this->addonEnvironment->addons());
    }

    /**
     */
    protected function registerClassResolvers()
    {
        $addons = $this->addonEnvironment->addons();

        AddonClassLoader::register($this->addonEnvironment, $addons);

        AliasResolver::register($this->app['path'], $addons, $this->app['config']->get('app.aliases'));
    }

    /**
     * setup package's commands.
     *
     * @param array $commands
     */
    protected function setupPackageCommands(array $commands)
    {
        foreach ($commands as $name => $class) {
            $this->app->singleton($name, function ($app) use ($class) {
                return $app->build($class);
            });
        }

        // Now register all the commands
        $this->commands(array_keys($commands));
    }

    /**
     * Register all addons.
     */
    public function registerAddons(array $addons)
    {
        foreach ($addons as $addon) {
            $addon->register($this->app);
        }
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //
        $this->registerBladeExtensions();

        // setup all addons
        $this->bootAddons();
    }

    /**
     * register blade extensions.
     */
    protected function registerBladeExtensions()
    {
        Blade::extend(BladeExtension::comment());

        Blade::extend(BladeExtension::script());
    }

    /**
     * setup & boot addons.
     */
    protected function bootAddons()
    {
        foreach ($this->addonEnvironment->addons() as $name => $addon) {
            // boot addon
            $addon->boot($this->app);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys(static::$commands);
    }
}
