<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\RouteListCommand as BaseCommand;

class RouteListCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] List all registered routes';

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app['router']);
    }
}
