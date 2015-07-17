<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use LaravelPlus\Extension\Hooks\ApplicationHook;

/**
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class AppContainerCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'app:container';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Lists object in application container';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $app = new ApplicationHook(app());

        $objects = $app->getInstances();
        $aliases = [];

        foreach ($app->getAliases() as $alias => $abstract) {
            $aliases[$abstract][] = $alias;
        }

        ksort($objects);

        foreach ($objects as $name => $instance) {
            $this->info($name.': ');

            $this->output($instance);

            if (isset($aliases[$name])) {
                foreach ($aliases[$name] as $value) {
                    echo "\t", '[alias] "', $value, '"', "\n";
                }
            }
        }
    }

    private function output($instance)
    {
        echo "\t";
        if (is_object($instance)) {
            echo 'object "', get_class($instance), '"';
        } elseif (is_string($instance)) {
            echo 'string "', $instance, '"';
        } elseif (is_bool($instance)) {
            echo 'bool ', $instance, '';
        } else {
            echo '(unknown) ', $instance, '';
        }
        echo "\n";
    }
}
