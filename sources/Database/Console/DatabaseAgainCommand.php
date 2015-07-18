<?php

namespace LaravelPlus\Extension\Database\Console;

use Jumilla\Versionia\Laravel\Console\DatabaseAgainCommand as BaseCommand;

class DatabaseAgainCommand extends BaseCommand
{
    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->description = '[+] '.$this->description;

        parent::__construct();
    }
}
