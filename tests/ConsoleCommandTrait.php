<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Console\Command;

trait ConsoleCommandTrait
{
    /**
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Console\Command $command
     * @param  array $arguments
     * @return int
     */
    protected function runCommand(Application $app, Command $command, array $arguments = [])
    {
        $command->setLaravel($app);

        $input = new Symfony\Component\Console\Input\ArrayInput($arguments);

        return $command->run($input, new Symfony\Component\Console\Output\NullOutput);
    }

    /**
     * @param  string $class
     * @param  array $arguments
     * @return int
     */
    protected function runCommandAndUserCancel($class, array $arguments = [])
    {
        $app = $this->createApplication();
        $app['env'] = 'production';

        $command = Mockery::mock($class.'[confirmToProceed]');
        $command->shouldReceive('confirmToProceed')->once()->andReturn(false);

        return $this->runCommand($app, $command, $arguments);
    }
}
