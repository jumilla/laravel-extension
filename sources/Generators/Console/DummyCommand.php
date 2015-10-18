<?php

namespace LaravelPlus\Extension\Generators\Console;

use Illuminate\Console\Command as BaseCommand;

class DummyCommand extends BaseCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = "_";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('This is the dummy command. no effect.');
    }
}
