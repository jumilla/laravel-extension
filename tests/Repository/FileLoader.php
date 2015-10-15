<?php

use LaravelPlus\Extension\Repository\FileLoader;

class FileLoaderTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new FileLoader();

        Assert::isInstanceOf(FileLoader::class, $command);
    }
}
