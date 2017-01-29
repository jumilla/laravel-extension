<?php

namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;

/**
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class HashCheckCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'hash:check
        {string1 : Plain or Hashed string one.}
        {string2 : Plain or Hashed string two.}
        {--cost=10 : Cost of generate.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Check hashed value';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cost = $this->option('cost');

        $string1 = $this->argument('string1');
        $string2 = $this->argument('string2');

        if ($this->isHashed($string1)) {
            $rawString = $string2;
            $hashedString = $string1;
        } elseif ($this->isHashed($string2)) {
            $rawString = $string1;
            $hashedString = $string2;
        } else {
            $this->error(sprintf('Error: both "%s" and "%s" are not hashed string.', $string1, $string2));

            return;
        }

        $result = app('hash')->check($string1, $string2);

        $this->info($result ? 'Check OK!' : 'Check NG.');
    }

    protected function isHashed($string)
    {
        return strlen($string) == 60 && starts_with($string, '$2y$');
    }
}
