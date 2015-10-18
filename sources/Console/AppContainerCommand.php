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
        $app = new ApplicationHook($this->laravel);

        $instances = $app->getInstances();
        $objects = $app->getBindings();
        $aliases = [];

        foreach ($app->getAliases() as $alias => $abstract) {
            $aliases[$abstract][] = $alias;
        }

        ksort($objects);

        foreach ($objects as $name => $instance) {
            $this->info($name.': ');

            if (is_array($instance)) {
                if ($instance['shared']) {
                    $this->output(array_get($instances, $name));
                }
                else {
                    $this->output($instance['concrete']);
                }
            }
            else {
                $this->output($instance);
            }

            if (isset($aliases[$name])) {
                foreach ($aliases[$name] as $value) {
                    $this->line("\t[alias] \"$value\"\n");
                }
            }
        }
    }

    private function output($instance)
    {
        $line = "\t";
        if (is_object($instance)) {
            $line .= 'object "'.get_class($instance).'"';
        } elseif (is_string($instance)) {
            $line .= 'string "'.$instance.'"';
        } elseif (is_bool($instance)) {
            $line .= 'bool '.$instance.'';
        } elseif (is_null($instance)) {
            $line .= 'null';
        } else {
            $line .= '(unknown) '.$instance;
        }
        $line .= "\n";

        $this->line($line);
    }
}
