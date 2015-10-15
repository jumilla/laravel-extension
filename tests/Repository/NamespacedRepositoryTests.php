<?php

use LaravelPlus\Extension\Repository\NamespacedRepository;
use LaravelPlus\Extension\Repository\FileLoader;

class NamespacedRepositoryTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $command = new NamespacedRepository(new FileLoader($app['files'], 'foo'));

        Assert::isInstanceOf(NamespacedRepository::class, $command);
    }
}
