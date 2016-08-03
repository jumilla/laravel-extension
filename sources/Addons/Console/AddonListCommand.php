<?php

namespace LaravelPlus\Extension\Addons\Console;

use Jumilla\Addomnipot\Laravel\Console\AddonListCommand as BaseCommand;

class AddonListCommand extends BaseCommand
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
