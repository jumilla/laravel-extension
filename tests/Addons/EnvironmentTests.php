<?php

use LaravelPlus\Extension\Addons\Environment;

class EnvironmentTests extends TestCase
{
    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $environment = new Environment();

        Assert::isInstanceOf(Environment::class, $environment);
    }
}
