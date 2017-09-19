<?php

namespace LaravelPlus\Extension\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use LaravelPlus\Extension\Commands;
use LaravelPlus\Extension\Addons;
use LaravelPlus\Extension\Database;
use LaravelPlus\Extension\Generators;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ClearResets' => 'command.auth.resets.clear',
        'ConfigCache' => 'command.config.cache',
        'ConfigClear' => 'command.config.clear',
        'Down' => 'command.down',
        'Environment' => 'command.environment',
        'KeyGenerate' => 'command.key.generate',
        // 'Migrate' => 'command.migrate',
        // 'MigrateFresh' => 'command.migrate.fresh',
        // 'MigrateInstall' => 'command.migrate.install',
        // 'MigrateRefresh' => 'command.migrate.refresh',
        // 'MigrateReset' => 'command.migrate.reset',
        // 'MigrateRollback' => 'command.migrate.rollback',
        // 'MigrateStatus' => 'command.migrate.status',
        'Optimize' => 'command.optimize',
        'PackageDiscover' => 'command.package.discover',
        'Preset' => 'command.preset',
        'QueueFailed' => 'command.queue.failed',
        'QueueFlush' => 'command.queue.flush',
        'QueueForget' => 'command.queue.forget',
        'QueueListen' => 'command.queue.listen',
        'QueueRestart' => 'command.queue.restart',
        'QueueRetry' => 'command.queue.retry',
        'QueueWork' => 'command.queue.work',
        'RouteCache' => 'command.route.cache',
        'RouteClear' => 'command.route.clear',
        'RouteList' => 'command.route.list',
        // 'Seed' => 'command.seed',
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'StorageLink' => 'command.storage.link',
        'Up' => 'command.up',
        'ViewClear' => 'command.view.clear',

        'Route' => 'command+.route',
        'Tail' => 'command+.tail',
        'AddonList' => 'command+.addon.list',
        'AddonStatus' => 'command+.addon.status',
        'DatabaseStatus' => 'command+.database.status',
        'DatabaseUpgrade' => 'command+.database.upgrade',
        'DatabaseClean' => 'command+.database.clean',
        'DatabaseRefresh' => 'command+.database.refresh',
        'DatabaseRollback' => 'command+.database.rollback',
        'DatabaseAgain' => 'command+.database.again',
        'DatabaseSeed' => 'command+.database.seed',
        'HashMake' => 'command+.hash.make',
        'HashCheck' => 'command+.hash.check',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'AppName' => 'command.app.name',
        // 'AuthMake' => 'command.auth.make',
        // 'CacheTable' => 'command.cache.table',
        // 'ConsoleMake' => 'command.console.make',
        // 'ControllerMake' => 'command.controller.make',
        'EventGenerate' => 'command.event.generate',
        // 'EventMake' => 'command.event.make',
        // 'JobMake' => 'command.job.make',
        // 'ListenerMake' => 'command.listener.make',
        // 'MailMake' => 'command.mail.make',
        // 'MiddlewareMake' => 'command.middleware.make',
        // 'MigrateMake' => 'command.migrate.make',
        // 'ModelMake' => 'command.model.make',
        // 'NotificationMake' => 'command.notification.make',
        // 'NotificationTable' => 'command.notification.table',
        // 'PolicyMake' => 'command.policy.make',
        // 'ProviderMake' => 'command.provider.make',
        // 'QueueFailedTable' => 'command.queue.failed-table',
        // 'QueueTable' => 'command.queue.table',
        // 'RequestMake' => 'command.request.make',
        // 'SeederMake' => 'command.seeder.make',
        // 'SessionTable' => 'command.session.table',
        'Serve' => 'command.serve',
        // 'TestMake' => 'command.test.make',
        'VendorPublish' => 'command.vendor.publish',

        'AppContainer' => 'command+.app.container',
        'AddonName' => 'command+.addon.name',
        'AddonRemove' => 'command+.addon.remove',

        'MakeAddon' => 'command+.addon.make',
        'MakeCommand' => 'command+.command.make',
        'MakeController' => 'command+.controller.make',
        'MakeEvent' => 'command+.event.make',
        'MakeJob' => 'command+.job.make',
        'MakeListener' => 'command+.listener.make',
        'MakeMail' => 'command+.mail.make',
        'MakeMiddleware' => 'command+.middleware.make',
        'MakeMigration' => 'command+.migration.make',
        'MakeModel' => 'command+.model.make',
        'MakeNotification' => 'command+.notification.make',
        'MakePolicy' => 'command+.policy.make',
        'MakeProvider' => 'command+.provider.make',
        'MakeRequest' => 'command+.request.make',
        'MakeSeeder' => 'command+.seeder.make',
        'MakeTest' => 'command+.test.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands($this->availableCommands());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->availableCommands());
    }

    /**
     * @return array
     */
    protected function availableCommands()
    {
        $commands = $this->commands;

        if ($this->app->environment() != 'production') {
            $commands = array_merge($commands, $this->devCommands);
        }

        return $commands;
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach ($commands as $name => $command) {
            $this->{"register{$name}Command"}($command);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerRouteCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Commands\RouteListCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerTailCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Commands\TailCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerAppContainerCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Commands\AppContainerCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerAddonListCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Addons\Commands\AddonListCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerAddonStatusCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Addons\Commands\AddonStatusCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerAddonNameCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Addons\Commands\AddonNameCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerAddonRemoveCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Addons\Commands\AddonRemoveCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseStatusCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseStatusCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseUpgradeCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseUpgradeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseCleanCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseCleanCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseRefreshCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseRefreshCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseRollbackCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseRollbackCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseAgainCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseAgainCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerDatabaseSeedCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\DatabaseSeedCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerHashMakeCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Commands\HashMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerHashCheckCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Commands\HashCheckCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeAddonCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Addons\Commands\AddonMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeCommandCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\CommandMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeControllerCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\ControllerMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeEventCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\EventMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeJobCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\JobMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeListenerCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\ListenerMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeMailCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\MailMakeCommand($app);
        });
    }


    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeMiddlewareCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\MiddlewareMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeMigrationCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\MigrationMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeModelCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\ModelMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeNotificationCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\NotificationMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakePolicyCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\PolicyMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeProviderCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\ProviderMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeRequestCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\RequestMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeSeederCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Database\Commands\SeederMakeCommand($app);
        });
    }

    /**
     * Register the command.
     *
     * @param string $command
     * @return void
     */
    protected function registerMakeTestCommand($command)
    {
        $this->app->singleton($command, function ($app) {
            return new Generators\Commands\TestMakeCommand($app);
        });
    }
}
