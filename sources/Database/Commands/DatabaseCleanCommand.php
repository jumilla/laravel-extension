<?php

namespace LaravelPlus\Extension\Database\Commands;

use Jumilla\Versionia\Laravel\Commands\DatabaseCleanCommand as BaseCommand;

class DatabaseCleanCommand extends BaseCommand
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
