<?php

use LaravelPlus\Extension\Providers\ArtisanServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Foundation;
use Illuminate\Auth;
use Illuminate\Cache;
use Illuminate\Queue;
use Illuminate\Console\Scheduling;
use LaravelPlus\Extension;
use LaravelPlus\Extension\Addons;
use LaravelPlus\Extension\Database;
use LaravelPlus\Extension\Generators;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class ArtisanServiceProviderTests extends TestCase
{
    public function test_registerOriginalCommands()
    {
        $app = $this->createApplication();

        $provider = new ComposerServiceProvider($app);
        $provider->register();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\EnvironmentCommand::class, $app->make('command.environment'));
        Assert::isInstanceOf(Foundation\Console\UpCommand::class, $app->make('command.up'));
        Assert::isInstanceOf(Foundation\Console\DownCommand::class, $app->make('command.down'));
        Assert::isInstanceOf(Foundation\Console\OptimizeCommand::class, $app->make('command.optimize'));
        Assert::isInstanceOf(Foundation\Console\ClearCompiledCommand::class, $app->make('command.clear-compiled'));
        Assert::isInstanceOf(Foundation\Console\KeyGenerateCommand::class, $app->make('command.key.generate'));
    }

    public function test_registerOriginalAuthCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Auth\Console\ClearResetsCommand::class, $app->make('command.auth.resets.clear'));
    }

    public function test_registerOriginalCacheCommands()
    {
        $app = $this->createApplication();
        $app['cache'] = new Cache\CacheManager($app);

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Cache\Console\ClearCommand::class, $app->make('command.cache.clear'));
        Assert::isInstanceOf(Cache\Console\ForgetCommand::class, $app->make('command.cache.forget'));
    }

    public function test_registerOriginalConfigCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\ConfigCacheCommand::class, $app->make('command.config.cache'));
        Assert::isInstanceOf(Foundation\Console\ConfigClearCommand::class, $app->make('command.config.clear'));
    }

    public function test_registerOriginalQueueCommands()
    {
        $app = $this->createApplication();
        $app['queue'] = new Queue\QueueManager($app);
        $app['queue.listener'] = new Queue\Listener($app->basePath());
        $app['queue.worker'] = new Queue\Worker($app['queue'], $app['events'], new ExceptionHandler($app));

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Queue\Console\ListFailedCommand::class, $app->make('command.queue.failed'));
        Assert::isInstanceOf(Queue\Console\FlushFailedCommand::class, $app->make('command.queue.flush'));
        Assert::isInstanceOf(Queue\Console\ForgetFailedCommand::class, $app->make('command.queue.forget'));
        Assert::isInstanceOf(Queue\Console\ListenCommand::class, $app->make('command.queue.listen'));
        Assert::isInstanceOf(Queue\Console\RestartCommand::class, $app->make('command.queue.restart'));
        Assert::isInstanceOf(Queue\Console\RetryCommand::class, $app->make('command.queue.retry'));
        Assert::isInstanceOf(Queue\Console\WorkCommand::class, $app->make('command.queue.work'));
    }

    public function test_registerOriginalRoutingCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\RouteListCommand::class, $app->make('command.route.list'));
        Assert::isInstanceOf(Foundation\Console\RouteCacheCommand::class, $app->make('command.route.cache'));
        Assert::isInstanceOf(Foundation\Console\RouteClearCommand::class, $app->make('command.route.clear'));
    }

    public function test_registerOriginalSchedulingCommands()
    {
        $app = $this->createApplication();
        $app[Scheduling\Schedule::class] = new Scheduling\Schedule(new Cache\Repository(new Cache\NullStore()));

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Scheduling\ScheduleRunCommand::class, $app->make(Scheduling\ScheduleRunCommand::class));
        Assert::isInstanceOf(Scheduling\ScheduleFinishCommand::class, $app->make(Scheduling\ScheduleFinishCommand::class));
    }

    public function test_registerOriginalStorageCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\StorageLinkCommand::class, $app->make('command.storage.link'));
    }

    public function test_registerOriginalViewCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\ViewClearCommand::class, $app->make('command.view.clear'));
    }

    public function test_registerOriginalDevCommands()
    {
        $app = $this->createApplication();

        $provider = new ComposerServiceProvider($app);
        $provider->register();

        $provider = new ArtisanServiceProvider($app);
        $provider->register();

        Assert::isInstanceOf(Foundation\Console\AppNameCommand::class, $app->make('command.app.name'));
        Assert::isInstanceOf(Foundation\Console\EventGenerateCommand::class, $app->make('command.event.generate'));
        Assert::isInstanceOf(Foundation\Console\ServeCommand::class, $app->make('command.serve'));
        Assert::isInstanceOf(Foundation\Console\VendorPublishCommand::class, $app->make('command.vendor.publish'));
    }

    public function test_registerPlusCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);

        $provider->register();

        Assert::isInstanceOf(Extension\Commands\RouteListCommand::class, $app->make('command+.route'));
        Assert::isInstanceOf(Extension\Commands\TailCommand::class, $app->make('command+.tail'));
        Assert::isInstanceOf(Extension\Commands\HashMakeCommand::class, $app->make('command+.hash.make'));
        Assert::isInstanceOf(Extension\Commands\HashCheckCommand::class, $app->make('command+.hash.check'));

        Assert::isInstanceOf(Addons\Commands\AddonListCommand::class, $app->make('command+.addon.list'));
        Assert::isInstanceOf(Addons\Commands\AddonStatusCommand::class, $app->make('command+.addon.status'));

        Assert::isInstanceOf(Database\Commands\DatabaseStatusCommand::class, $app->make('command+.database.status'));
        Assert::isInstanceOf(Database\Commands\DatabaseUpgradeCommand::class, $app->make('command+.database.upgrade'));
        Assert::isInstanceOf(Database\Commands\DatabaseCleanCommand::class, $app->make('command+.database.clean'));
        Assert::isInstanceOf(Database\Commands\DatabaseRefreshCommand::class, $app->make('command+.database.refresh'));
        Assert::isInstanceOf(Database\Commands\DatabaseRollbackCommand::class, $app->make('command+.database.rollback'));
        Assert::isInstanceOf(Database\Commands\DatabaseAgainCommand::class, $app->make('command+.database.again'));
        Assert::isInstanceOf(Database\Commands\DatabaseSeedCommand::class, $app->make('command+.database.seed'));
    }

    public function test_registerPlusDevCommands()
    {
        $app = $this->createApplication();

        $provider = new ArtisanServiceProvider($app);

        $provider->register();

        Assert::isInstanceOf(Extension\Commands\AppContainerCommand::class, $app->make('command+.app.container'));

        Assert::isInstanceOf(Addons\Commands\AddonNameCommand::class, $app->make('command+.addon.name'));
        Assert::isInstanceOf(Addons\Commands\AddonRemoveCommand::class, $app->make('command+.addon.remove'));

        Assert::isInstanceOf(Addons\Commands\AddonMakeCommand::class, $app->make('command+.addon.make'));
        Assert::isInstanceOf(Generators\Commands\CommandMakeCommand::class, $app->make('command+.command.make'));
        Assert::isInstanceOf(Generators\Commands\ControllerMakeCommand::class, $app->make('command+.controller.make'));
        Assert::isInstanceOf(Generators\Commands\EventMakeCommand::class, $app->make('command+.event.make'));
        Assert::isInstanceOf(Generators\Commands\JobMakeCommand::class, $app->make('command+.job.make'));
        Assert::isInstanceOf(Generators\Commands\ListenerMakeCommand::class, $app->make('command+.listener.make'));
        Assert::isInstanceOf(Generators\Commands\MailMakeCommand::class, $app->make('command+.mail.make'));
        Assert::isInstanceOf(Generators\Commands\MiddlewareMakeCommand::class, $app->make('command+.middleware.make'));
        Assert::isInstanceOf(Database\Commands\MigrationMakeCommand::class, $app->make('command+.migration.make'));
        Assert::isInstanceOf(Generators\Commands\ModelMakeCommand::class, $app->make('command+.model.make'));
        Assert::isInstanceOf(Generators\Commands\NotificationMakeCommand::class, $app->make('command+.notification.make'));
        Assert::isInstanceOf(Generators\Commands\PolicyMakeCommand::class, $app->make('command+.policy.make'));
        Assert::isInstanceOf(Generators\Commands\ProviderMakeCommand::class, $app->make('command+.provider.make'));
        Assert::isInstanceOf(Generators\Commands\RequestMakeCommand::class, $app->make('command+.request.make'));
        Assert::isInstanceOf(Database\Commands\SeederMakeCommand::class, $app->make('command+.seeder.make'));
        Assert::isInstanceOf(Generators\Commands\TestMakeCommand::class, $app->make('command+.test.make'));
    }
}
