<?php

namespace LaravelPlus\Extension\Database\Console;

use Jumilla\Versionia\Laravel\Console\DatabaseStatusCommand as BaseCommand;

class DatabaseStatusCommand extends BaseCommand
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
