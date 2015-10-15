<?php

use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonDirectoryTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new AddonDirectory();

        Assert::isInstanceOf(AddonDirectory::class, $command);
    }
}
