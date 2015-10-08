<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command as BaseCommand;

class DummyCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = '_';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    }
}
