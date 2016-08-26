<?php

namespace LaravelPlus\Extension\Generators;

use Illuminate\Contracts\Foundation\Application;
use LaravelPlus\Extension\Database;

class GeneratorCommandRegistrar
{
    /**
     * @var array
     */
    protected static $commands = [
        // make:
        'command+.command.make' => Console\CommandMakeCommand::class,
        'command+.controller.make' => Console\ControllerMakeCommand::class,
        'command+.event.make' => Console\EventMakeCommand::class,
        'command+.job.make' => Console\JobMakeCommand::class,
        'command+.listener.make' => Console\ListenerMakeCommand::class,
        'command+.mail.make' => Console\MailMakeCommand::class,
        'command+.middleware.make' => Console\MiddlewareMakeCommand::class,
        'command+.migration.make' => Database\Console\MigrationMakeCommand::class,
        'command+.model.make' => Console\ModelMakeCommand::class,
        'command+.notification.make' => Console\NotificationMakeCommand::class,
        'command+.policy.make' => Console\PolicyMakeCommand::class,
        'command+.provider.make' => Console\ProviderMakeCommand::class,
        'command+.request.make' => Console\RequestMakeCommand::class,
        'command+.seeder.make' => Database\Console\SeederMakeCommand::class,
        'command+.test.make' => Console\TestMakeCommand::class,
    ];

    /**
     * @var array
     */
    protected static $legacy_commands = [
//        'command.command.make' => Console\DummyCommand::class,
//        'command.handler.command' => Console\DummyCommand::class,
//        'command.handler.event' => Console\DummyCommand::class,
    ];

    /**
     * The constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register generator commands.
     */
    public function register()
    {
        $this->registerCommands(static::$commands);

        $this->silentLegacyCommands(static::$legacy_commands);

        return array_keys(static::$commands);
    }

    /**
     * Register commands.
     *
     * @param array $commands
     */
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
     * Setup legacy framework's commands.
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
