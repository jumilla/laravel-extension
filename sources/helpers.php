<?php

use LaravelPlus\Extension\Specs\Factory as SpecFactory;

if (!function_exists('runtime_get_caller_class')) {
    /**
     * @param int $level
     *
     * @return string
     */
    function runtime_get_caller_class($level = 1)
    {
        $level += 1;

        $caller = debug_backtrace(0, $level)[$level - 1];

        return array_get($caller, 'class');
    }
}

if (!function_exists('spec')) {
    /**
     * Get spec.
     *
     * @param string $path
     *
     * @return \LaravelPlus\Extension\Specs\Factory|LaravelPlus\Extension\Specs\InputSpec
     */
    function spec($path = null)
    {
        $factory = app(SpecFactory::class);

        if (func_num_args() == 0) {
            return $factory;
        }

        return $factory->make($path);
    }
}
