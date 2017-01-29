<?php

namespace LaravelPlus\Extension\Addons\Console;

use Jumilla\Addomnipot\Laravel\Commands\AddonStatusCommand as BaseCommand;

class AddonStatusCommand extends BaseCommand
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
