<?php

use LaravelPlus\Extension\AliasResolver;

class AliasResolverTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $command = new AliasResolver();

        Assert::isInstanceOf(AliasResolver::class, $command);
    }
}
