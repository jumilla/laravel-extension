<?php

namespace LaravelPlus\Extension\Database\Commands;

use Jumilla\Versionia\Laravel\Commands\DatabaseRefreshCommand as BaseCommand;

class DatabaseRefreshCommand extends BaseCommand
{
    /**
     * Create a new console command instance.
     */
    public function __construct()
    {
        $this->description = '[+] '.$this->description;

        parent::__construct();
    }
}
