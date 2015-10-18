<?php

use LaravelPlus\Extension\Addons\Addon;
use Illuminate\Config\Repository;
use Illuminate\Translation\Translator;

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
        $addon = $this->getAddon('foo');
        $addon->register($app);
        $addon->boot($app);

        Assert::success();
    }

    public function test_attributeAccessMethods()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'namespace' => 'Foo\\',
            ],
        ]));
        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same($app->basePath().'/addons/foo/bar', $addon->path('bar'));
        Assert::same('addons/foo', $addon->relativePath());
        Assert::same(5, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_resourceAccessMethods()
    {
        $app = $this->createApplication();
        $app['translator'] = $this->createMock(Translator::class);
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository);

        $app['translator']->shouldReceive('trans')->with('foo::foo', [], 'messages', null)->andReturn('bar')->once();
        $app['translator']->shouldReceive('transChoice')->with('foo::foo', 1, [], 'messages', null)->andReturn('bar')->once();

        Assert::same('bar', $addon->config('foo', 'bar'));
        Assert::same('bar', $addon->trans('foo'));
        Assert::same('bar', $addon->transChoice('foo', 1));
    }

    public function test_registerV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 5,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->register($app);

        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same(5, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_bootV5Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 5,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->boot($app);
    }

    public function test_registerV4Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 4,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->register($app);

        Assert::same('foo', $addon->name());
        Assert::same($app->basePath().'/addons/foo', $addon->path());
        Assert::same(4, $addon->version());
        Assert::same('Foo', $addon->phpNamespace());
    }

    public function test_bootV4Addon()
    {
        $app = $this->createApplication();
        $addon = new Addon('foo', $app->basePath().'/addons/foo', new Repository([
            'addon' => [
                'version' => 4,
                'namespace' => 'Foo',
            ],
        ]));

        $addon->boot($app);
    }

    protected function getAddon($name)
    {
        $this->createAddon($name, 'ui', [
            'namespace' => 'Foo',
            'addon_class' => 'Bar',
            'languages' => ['en'],
        ]);

        $path = $this->app->basePath().'/addons/'.$name;

        return Addon::create($path);
    }
}
