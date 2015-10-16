<?php

use LaravelPlus\Extension\Addons\Addon;

class AddonTests extends TestCase
{
    public function test_createNoExistingAddon()
    {
        try {
            Addon::create('foo');

            Assert::failure();
        }
        catch (RuntimeException $ex) {
            Assert::success();
        }
    }

    public function test_createExistingAddon()
    {
        $app = $this->createApplication();
        $this->createAddon('foo', 'ui', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        $path = $this->app->basePath().'/addons/foo';

        $addon = Addon::create($path);

        $addon->register($app);
        $addon->boot($app);

        Assert::same($path, $addon->path());
        Assert::same($path.'/bar', $addon->path('bar'));
        Assert::same('addons/foo', $addon->relativePath());
    }
}
