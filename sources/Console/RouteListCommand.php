<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\RouteListCommand as BaseCommand;

class RouteListCommand extends BaseCommand
{
    /**
     * The console command singature.
     *
     * @var string
     */
    protected $singature = 'route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] shortcut to `route:list`';

    /**
     * Create a new route command instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        //        $this->description = '[+] '.$this->description;
        parent::__construct($app['router']);
    }
}
