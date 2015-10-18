<?php

use LaravelPlus\Extension\Addons\Directory as AddonDirectory;

class AddonDirectoryTests extends TestCase
{
    public function test_classToPathMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonDirectory();

        Assert::same('DatabaseServiceProvider.php', $instance->classToPath('DatabaseServiceProvider'));
        Assert::same('Providers/DatabaseServiceProvider.php', $instance->classToPath('Providers\DatabaseServiceProvider'));
    }

    public function test_pathToClassMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonDirectory();

        Assert::same('DatabaseServiceProvider', $instance->pathToClass('DatabaseServiceProvider.php'));
        Assert::same('Providers\DatabaseServiceProvider', $instance->pathToClass('Providers/DatabaseServiceProvider.php'));
    }

    public function test_addonsMethod()
    {
        $app = $this->createApplication();
        $instance = new AddonDirectory();

        $app['config']->set('addon.path', 'tmp');

        Assert::same([], $instance->addons());
    }
}
