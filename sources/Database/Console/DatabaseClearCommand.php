<?php

namespace LaravelPlus\Extension\Database\Console;

use Jumilla\Versionia\Laravel\Console\DatabaseClearCommand as BaseCommand;

class DatabaseClearCommand extends BaseCommand
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
