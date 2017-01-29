<?php

namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\RouteListCommand as BaseCommand;

class RouteListCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Shortcut to `route:list`';

    /**
     * Create a new route command instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app['router']);
    }
}
