<?php

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Cache\NullStore;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Routing\Router;
use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;
use Jumilla\Versionia\Laravel\Migrator;

abstract class TestCase extends PHPUnit\Framework\TestCase
{
    use MockeryTrait;

    /**
     * @before
     */
    public function setupSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox');
        $files->makeDirectory(__DIR__.'/sandbox/addons');
        $files->makeDirectory(__DIR__.'/sandbox/app');
        $files->makeDirectory(__DIR__.'/sandbox/config');
    }

    /**
     * @after
     */
    public function teardownSandbox()
    {
        $files = new Filesystem();
        $files->deleteDirectory(__DIR__.'/sandbox');
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function createApplication()
    {
        Container::setInstance($this->app = new ApplicationStub([
        ]));

        $this->app[Illuminate\Contracts\Foundation\Application::class] = $this->app;
        $this->app[Illuminate\Contracts\Cache\Repository::class] = new Cache(new NullStore());
        $this->app['config'] = new Config([]);
        $this->app['events'] = new Dispatcher($this->app);
        $this->app['files'] = new Filesystem();
        $this->app['filesystem'] = new FilesystemManager($this->app);
        $this->app['router'] = new Router($this->app['events'], $this->app);
        
        return $this->app;
    }

    protected function createMigrator(array $overrides = null)
    {
        $this->app['db'] = $this->createMock(DatabaseManager::class);

        $migrator = $this->createMock(Migrator::class, $overrides, function () {
            return [$this->app['db']];
        });

        $this->app->instance('database.migrator', $migrator);
        $this->app->alias('database.migrator', Migrator::class);

        return $migrator;
    }

    protected function createAddon($name, $type, array $arguments)
    {
        (new AddonGenerator())->generateAddon($this->app->basePath().'/addons/'.$name, $type, $arguments);
    }
}
