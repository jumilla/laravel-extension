<?php

use LaravelPlus\Extension\Addons\Console\AddonStatusCommand as Command;

class AddonStatusCommandTest extends TestCase
{
    use ConsoleCommandTrait;

    /**
     * @test
     */
    public function test_withNoParameter()
    {
        // 1. setup
        $app = $this->createApplication();
        $app['config'] = new Illuminate\Config\Repository([]);
        $app['files'] = new Illuminate\Filesystem\Filesystem();
        $app['path.base'] = __DIR__.'/../sandbox';
        $app['path.config'] = __DIR__.'/../sandbox/config';

        @mkdir($app['path.config'], 0755, true);

        // 2. condition

        // 3. test
        $command = new Command();

        $this->runCommand($app, $command);
    }
}
