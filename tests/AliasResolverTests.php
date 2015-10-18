<?php

use LaravelPlus\Extension\AliasResolver;
use LaravelPlus\Extension\Addons\Addon;
use Illuminate\Config\Repository;

class AliasResolverTests extends TestCase
{
    public function test_registerAndUnregisterMethod()
    {
        AliasResolver::register(__DIR__, [], []);
        AliasResolver::unregister();
    }

    public function test_loadMethod()
    {
        $addon1 = new Addon('foo', __DIR__.'/../addons/foo', new Repository([
            'addon' => [
                'namespace' => 'Foo',
                'aliases' => [
                    'Model' => 'Illuminate\Database\Eloquent\Model',
                ],
                'includes_global_aliases' => true,
            ],
        ]));
        $addon2 = new Addon('bar', __DIR__.'/../addons/bar', new Repository([
            'addon' => [
                'namespace' => '',
            ],
        ]));

        $resolver = new AliasResolver(__DIR__.'/../app', [$addon1, $addon2], [
            'Controller' => 'Illuminate\Routing\Controller',
        ]);

        Assert::true($resolver->load('Foo\Controller'));
        Assert::true($resolver->load('Foo\Model'));
        Assert::false($resolver->load('Nothing'));

        AliasResolver::unregister();
    }
}
