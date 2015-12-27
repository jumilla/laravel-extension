<?php

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Jumilla\Addomnipot\Laravel\Generator as AddonGenerator;
use Jumilla\Versionia\Laravel\Migrator;

abstract class TestCase extends PHPUnit_Framework_TestCase
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
        $this->app['config'] = new Config([]);
        $this->app['files'] = new Filesystem();
        $this->app['filesystem'] = new FilesystemManager($this->app);

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
