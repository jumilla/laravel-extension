<?php

namespace LaravelPlus\Extension\Addons\Commands;

use Jumilla\Addomnipot\Laravel\Commands\AddonRemoveCommand as BaseCommand;

class AddonRemoveCommand extends BaseCommand
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
