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
        $this->createApplication();
        $this->createAddon('foo', 'minimum', [
            'namespace' => 'Foo',
        ]);

        $addon = Addon::create($this->app->basePath().'/addons/foo');

        Assert::isInstanceOf(Addon::class, $addon);
    }
}
