<?php

use LaravelPlus\Extension\Addons\AddonGenerator;

class AddonGeneratorTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new AddonGenerator();

        Assert::isInstanceOf(AddonGenerator::class, $command);
    }

    public function test_makeMinimum()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'minimum', [
            'namespace' => 'Foo',
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeSimple()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'simple', [
            'namespace' => 'Foo',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeLibrary()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'library', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeApi()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'api', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeUi()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'ui', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeDebug()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'debug', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeGenerator()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'generator', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeLaravel5()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'laravel5', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeSampleUI()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'sample-ui', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }

    public function test_makeSampleAuth()
    {
        $generator = new AddonGenerator();
        $path = __DIR__.'/../sandbox/addons/foo';

        $generator->generateAddon($path, 'sample-auth', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        Assert::fileExists($path.'/addon.php');
    }
}
