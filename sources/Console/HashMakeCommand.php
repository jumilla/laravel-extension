<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command;

/**
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class HashMakeCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'hash:make
        {string : Plain string.}
        {--cost=10 : Cost of generate.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Make hashed value';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cost = $this->option('cost');

        $hashed = app('hash')->make($this->argument('string'), [
            'rounds' => $cost,
        ]);

        $this->info(sprintf('Generated hash: "%s"', $hashed));
    }
}
