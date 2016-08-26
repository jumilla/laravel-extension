<?php

use LaravelPlus\Extension\Generators\GeneratorCommandRegistrar;
use LaravelPlus\Extension\Generators;
use LaravelPlus\Extension\Database;

class GeneratorCommandRegistrarTests extends TestCase
{
    public function test_registerMethod()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $commands = $registrar->register();

        Assert::containsAll([
            'command+.command.make',
            'command+.controller.make',
            'command+.event.make',
            'command+.job.make',
            'command+.listener.make',
            'command+.mail.make',
            'command+.middleware.make',
            'command+.migration.make',
            'command+.model.make',
            'command+.notification.make',
            'command+.policy.make',
            'command+.provider.make',
            'command+.request.make',
            'command+.seeder.make',
            'command+.test.make',
        ], $commands);
    }

    public function test_makeRegisteredCommands()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $registrar->register();

        Assert::isInstanceOf(Generators\Console\CommandMakeCommand::class, $app->make('command+.command.make'));
        Assert::isInstanceOf(Generators\Console\ControllerMakeCommand::class, $app->make('command+.controller.make'));
        Assert::isInstanceOf(Generators\Console\EventMakeCommand::class, $app->make('command+.event.make'));
        Assert::isInstanceOf(Generators\Console\JobMakeCommand::class, $app->make('command+.job.make'));
        Assert::isInstanceOf(Generators\Console\ListenerMakeCommand::class, $app->make('command+.listener.make'));
        Assert::isInstanceOf(Generators\Console\MailMakeCommand::class, $app->make('command+.mail.make'));
        Assert::isInstanceOf(Generators\Console\MiddlewareMakeCommand::class, $app->make('command+.middleware.make'));
        Assert::isInstanceOf(Database\Console\MigrationMakeCommand::class, $app->make('command+.migration.make'));
        Assert::isInstanceOf(Generators\Console\ModelMakeCommand::class, $app->make('command+.model.make'));
        Assert::isInstanceOf(Generators\Console\NotificationMakeCommand::class, $app->make('command+.notification.make'));
        Assert::isInstanceOf(Generators\Console\PolicyMakeCommand::class, $app->make('command+.policy.make'));
        Assert::isInstanceOf(Generators\Console\ProviderMakeCommand::class, $app->make('command+.provider.make'));
        Assert::isInstanceOf(Generators\Console\RequestMakeCommand::class, $app->make('command+.request.make'));
        Assert::isInstanceOf(Database\Console\SeederMakeCommand::class, $app->make('command+.seeder.make'));
        Assert::isInstanceOf(Generators\Console\TestMakeCommand::class, $app->make('command+.test.make'));
    }

    public function test_makeRegisteredCommands_alreadyRegistered()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $app['command+.model.make'] = new stdClass();

        $registrar->register();

        Assert::true($app->bound('command+.model.make'));
        Assert::isInstanceOf(Generators\Console\ModelMakeCommand::class, $app->make('command+.model.make'));
    }

/*
    public function test_makeLegacyCommands()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $registrar->register();

        $app['command.command.make'] = new stdClass();
        $app['command.handler.command'] = new stdClass();
        $app['command.handler.event'] = new stdClass();

        Assert::isInstanceOf(Generators\Console\DummyCommand::class, $app->make('command.command.make'));
        Assert::isInstanceOf(Generators\Console\DummyCommand::class, $app->make('command.handler.command'));
        Assert::isInstanceOf(Generators\Console\DummyCommand::class, $app->make('command.handler.event'));
    }

    public function test_makeLegacyCommands_notRegistered()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $registrar->register();

        Assert::false($app->bound('command.command.make'));
        Assert::false($app->bound('command.handler.command'));
        Assert::false($app->bound('command.handler.event'));
    }
*/
}
