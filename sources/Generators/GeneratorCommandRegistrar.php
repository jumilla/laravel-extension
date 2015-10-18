<?php

namespace LaravelPlus\Extension\Generators;

use Illuminate\Contracts\Foundation\Application as App;

class GeneratorCommandRegistrar
{
    /**
     * @var array
     */
    protected static $commands = [
        // make:
        'command+.console.make' => Generators\Console\ConsoleMakeCommand::class,
        'command+.controller.make' => Generators\Console\ControllerMakeCommand::class,
        'command+.event.make' => Generators\Console\EventMakeCommand::class,
        'command+.job.make' => Generators\Console\JobMakeCommand::class,
        'command+.listener.make' => Generators\Console\ListenerMakeCommand::class,
        'command+.middleware.make' => Generators\Console\MiddlewareMakeCommand::class,
        'command+.migration.make' => Database\Console\MigrationMakeCommand::class,
        'command+.model.make' => Generators\Console\ModelMakeCommand::class,
        'command+.policy.make' => Generators\Console\PolicyMakeCommand::class,
        'command+.provider.make' => Generators\Console\ProviderMakeCommand::class,
        'command+.request.make' => Generators\Console\RequestMakeCommand::class,
        'command+.seeder.make' => Database\Console\SeederMakeCommand::class,
        'command+.test.make' => Generators\Console\TestMakeCommand::class,
    ];

    /**
     * @var array
     */
    protected static $legacy_commands = [
        'command.command.make' => Console\DummyCommand::class,
        'command.handler.command' => Console\DummyCommand::class,
        'command.handler.event' => Console\DummyCommand::class,
    ];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->registerCommands(static::$commands);

        $this->silentLegacyCommands(static::$legacy_commands);

        return array_keys(static::$commands);
    }

    protected function registerCommands(array $commands)
    {
        foreach ($commands as $name => $class) {
            if ($this->app->bound($name)) {
                $this->app->extend($name, function ($instance, $app) use ($class) {
                    return $app->build($class);
                });
            }
            else {
                $this->app->singleton($name, function ($app) use ($class) {
                    return $app->build($class);
                });
            }
        }
    }

    /**
     * setup legacy framework's commands.
     *
     * @param array $commands
     */
    protected function silentLegacyCommands(array $commands)
    {
        foreach ($commands as $name => $class) {
            $this->app->extend($name, function ($instance, $app) use ($class) {
                return $app->build($class);
            });
        }
    }
}
