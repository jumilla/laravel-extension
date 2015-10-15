<?php

use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonDirectoryTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new AddonDirectory();

        Assert::isInstanceOf(AddonDirectory::class, $command);
    }
}
