<?php

use LaravelPlus\Extension\AliasResolver;

class AliasResolverTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new AliasResolver([], []);

        Assert::isInstanceOf(AliasResolver::class, $command);
    }
}
