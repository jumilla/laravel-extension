<?php

use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
//use Illuminate\Database\DatabaseManager;
use LaravelPlus\Extension\Addons\AddonGenerator;

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
//            'db' => DatabaseManager::class,
        ]));

        $this->app['config'] = new Config([]);
        $this->app['files'] = new Filesystem();
        $this->app['filesystem'] = new FilesystemManager($this->app);

        return $this->app;
    }

    protected function createAddon($name, $type, array $arguments)
    {
        (new AddonGenerator())->generateAddon($this->app->basePath().'/addons/'.$name, $type, $arguments);
    }
}
