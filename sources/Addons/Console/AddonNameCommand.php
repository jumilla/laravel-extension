<?php

namespace LaravelPlus\Extension\Addons\Console;

use Jumilla\Addomnipot\Laravel\Commands\AddonNameCommand as BaseCommand;

class AddonNameCommand extends BaseCommand
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
