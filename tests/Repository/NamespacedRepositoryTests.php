<?php

use LaravelPlus\Extension\Repository\NamespacedRepository;

class NamespacedRepositoryTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new NamespacedRepository();

        Assert::isInstanceOf(NamespacedRepository::class, $command);
    }
}
