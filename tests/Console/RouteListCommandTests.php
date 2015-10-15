<?php

use LaravelPlus\Extension\Console\RouteListCommand as Command;
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

        Assert::isInstanceOf(Command::class, $command);
    }
}
