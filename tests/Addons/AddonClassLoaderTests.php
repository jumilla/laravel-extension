<?php

use LaravelPlus\Extension\Addons\AddonClassLoader;

class AddonClassLoaderTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new AddonClassLoader();

        Assert::isInstanceOf(AddonClassLoader::class, $command);
    }
}
