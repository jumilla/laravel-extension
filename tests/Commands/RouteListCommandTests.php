<?php

use LaravelPlus\Extension\Commands\RouteListCommand as Command;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;

class RouteListCommandTests extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $app['router'] = new Router(new Dispatcher);

        $command = new Command($app);

        $result = $this->runCommand($app, $command);

        Assert::same(0, $result);
    }
}
