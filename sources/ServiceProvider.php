<?php

namespace LaravelPlus\Extension;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Addons\AddonDirectory;
use LaravelPlus\Extension\Addons\AddonClassLoader;
use LaravelPlus\Extension\Repository;
use LaravelPlus\Extension\Templates\BladeExtension;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected static $commands = [
// app:
        'command+.app.container' => Console\AppContainerCommand::class,
        'command+.app.route' => Console\RouteListCommand::class,
        'command+.app.tail' => Console\TailCommand::class,
// addon:
        'command+.addon.setup' => Addons\Console\AddonSetupCommand::class,
        'command+.addon.list' => Addons\Console\AddonListCommand::class,
        'command+.addon.make' => Addons\Console\AddonMakeCommand::class,
        'command+.addon.remove' => Addons\Console\AddonRemoveCommand::class,
        'command+.addon.check' => Addons\Console\AddonCheckCommand::class,
// database:
        'command+.database.status' => Database\Console\DatabaseStatusCommand::class,
        'command+.database.upgrade' => Database\Console\DatabaseUpgradeCommand::class,
        'command+.database.clear' => Database\Console\DatabaseClearCommand::class,
        'command+.database.rollback' => Database\Console\DatabaseRollbackCommand::class,
        'command+.database.refresh' => Database\Console\DatabaseRefreshCommand::class,
// hash:
        'command+.hash.make' => Console\HashMakeCommand::class,
        'command+.hash.check' => Console\HashCheckCommand::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @var array
     */
    protected $addons;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // register spec path for app
        $app['path.specs'] = $app->basePath().'/resources/specs';

        // register spec repository
        $this->app['specs'] = $this->app->share(function ($app) {
            $loader = new Repository\FileLoader($app['files'], $app['path.specs']);

            return new Repository\NamespacedRepository($loader);
        });

        // MEMO 現在はクラスファイルの解決を動的に行うモードのみ実装している。
//		$this->loadAutoloadFiles(AddonDirectory::path());

        AddonClassLoader::register(Application::getAddons());
        AliasResolver::register(Application::getAddons(), $app['config']->get('app.aliases'));

        // register all addons
        $this->registerAddons();
    }

    /**
     * setup & boot addons.
     *
     * @return void
     */
    protected function registerAddons()
    {
        foreach (Application::getAddons() as $addon) {
            // register addon
            $addon->register($this->app);
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Add package commands
        $this->setupCommands(static::$commands);

        //
        $this->registerBladeExtensions();

        // setup all addons
        $this->bootAddons();
    }

    /**
     * setup package's commands.
     *
     * @param  $command array
     * @return void
     */
    protected function setupCommands($commands)
    {
        foreach ($commands as $name => $class) {
            $this->app->singleton($name, function ($app) use ($class) {
                return new $class($app);
            });
        }

        // Now register all the commands
        $this->commands(array_keys(static::$commands));
    }

    /**
     * register blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        \Blade::extend(BladeExtension::comment());

        \Blade::extend(BladeExtension::script());
    }

    /**
     * setup & boot addons.
     *
     * @return void
     */
    protected function bootAddons()
    {
        foreach (Application::getAddons() as $name => $addon) {
            // register package
            $this->registerPackage($name, $addon);

            // boot addon
            $addon->boot($this->app);
        }
    }

    /**
     * Register the package's component namespaces.
     *
     * @param  string  $namespace
     * @param  string  $path
     * @return void
     */
    protected function registerPackage($namespace, $addon)
    {
        $lang = $addon->path($addon->config('addon.paths.lang', 'lang'));
        if (is_dir($lang)) {
            $this->app['translator']->addNamespace($namespace, $lang);
        }

        $view = $addon->path($addon->config('addon.paths.views', 'views'));
        if (is_dir($view)) {
            $this->app['view']->addNamespace($namespace, $view);
        }

        $spec = $addon->path($addon->config('addon.paths.specs', 'specs'));
        if (is_dir($spec)) {
            $this->app['specs']->addNamespace($namespace, $spec);
        }
    }

    /**
     * load 'autoload.php' files.
     *
     * @param  $path string
     * @return void
     */
    protected function loadAutoloadFiles($path)
    {
        // We will use the finder to locate all "autoload.php" files in the workbench
        // directory, then we will include them each so that they are able to load
        // the appropriate classes and file used by the given workbench package.
        $files = $this->app['files'];

        $autoloads = Finder::create()->in($path)->files()->name('autoload.php')->depth('<= 3')->followLinks();

        foreach ($autoloads as $file) {
            $files->requireOnce($file->getRealPath());
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
