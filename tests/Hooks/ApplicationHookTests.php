<?php

use LaravelPlus\Extension\Hooks\ApplicationHook;
use Illuminate\Contracts\Foundation\Application;

class ApplicationHookTests extends TestCase
{
    public function test_getResolvedMethod()
    {
        $app = $this->createApplication();

        $hook = new ApplicationHook($app);

        Assert::notNull($hook->getResolved());
    }

    public function test_getBindingsMethod()
    {
        $app = $this->createApplication();
        $app->bind('foo', 'bar');

        $hook = new ApplicationHook($app);

        Assert::arrayHasKey('foo', $hook->getBindings());
    }

    public function test_getInstancesMethod()
    {
        $app = $this->createApplication();
        $app->instance('foo', 'bar');

        $hook = new ApplicationHook($app);

        Assert::same(['foo' => 'bar'], $hook->getInstances());
    }

    public function test_getAliasesMethod()
    {
        $app = $this->createApplication();
        $app->alias('foo', 'bar');

        $hook = new ApplicationHook($app);

        Assert::same(['bar' => 'foo'], $hook->getAliases());
    }

    public function test_getTagsMethod()
    {
        $app = $this->createApplication();

        $hook = new ApplicationHook($app);

        Assert::same([], $hook->getTags());
    }

}
