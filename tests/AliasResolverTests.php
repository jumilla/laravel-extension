<?php

use LaravelPlus\Extension\AliasResolver;

class AliasResolverTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $resolver = new AliasResolver($app->basePath().'/app', [], []);

        Assert::isInstanceOf(AliasResolver::class, $resolver);
    }
}
