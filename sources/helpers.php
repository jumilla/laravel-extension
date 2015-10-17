<?php

use LaravelPlus\Extension\Addons\Environment as AddonEnvironment;

if (!function_exists('addon_name')) {
    /**
     * @param string $class
     * @param int $level
     *
     * @return string|null
     */
    function addon_name($class = null, $level = 2)
    {
        if ($class === null) {
            $caller = debug_backtrace(false, $level)[$level - 1];

            if (!isset($caller['class'])) {
                return;
            }

            $class = $caller['class'];
        }

        foreach (app(AddonEnvironment::class)->getAddons() as $addon) {
            if (starts_with($class, $addon->phpNamespace())) {
                return $addon->name();
            }
        }

        return;
    }
}

if (!function_exists('addon_namespace')) {
    /**
     * @param string $class
     * @param int $level
     *
     * @return string
     */
    function addon_namespace($class = null, $level = 2)
    {
        if ($class === null) {
            $caller = debug_backtrace(false, $level)[$level - 1];

            if (!isset($caller['class'])) {
                return '';
            }

            $class = $caller['class'];
        }

        $name = addon_name($class);

        return $name ? $name.'::' : '';
    }
}

if (!function_exists('addon')) {
    /**
     * @param string $name Addon name.
     *
     * @return LaravelPlus\Extension\Addons\Addon
     */
    function addon($name = null)
    {
        return app(AddonEnvironment::class)->getAddon($name ?: addon_name(null, 3));
    }
}

if (!function_exists('addon_path')) {
    /**
     * @param string      $name Addon name.
     * @param string|null $path
     *
     * @return mixed
     */
    function addon_path($name, $path = null)
    {
        return addon($name)->path($path);
    }
}

if (!function_exists('addon_config')) {
    /**
     * @param string $name  Addon name.
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    function addon_config($name, $key)
    {
        return call_user_func_array([addon($name), 'config'], array_slice(func_get_args(), 1));
    }
}

if (!function_exists('addon_trans')) {
    /**
     * Translate the given message.
     *
     * @param string $name
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    function addon_trans($name, $id)
    {
        return call_user_func_array([addon($name), 'trans'], array_slice(func_get_args(), 1));
    }
}

if (!function_exists('addon_trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $id
     * @param  int     $number
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     *
     * @return string
     */
    function addon_trans_choice($name, $id)
    {
        return call_user_func_array([addon($name), 'transChoice'], array_slice(func_get_args(), 1));
    }
}

if (!function_exists('addon_view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     *
     * @return Illuminate\View\View
     */
    function addon_view($name, $view)
    {
        return call_user_func_array([addon($name), 'view'], array_slice(func_get_args(), 1));
    }
}

if (!function_exists('addon_spec')) {
    /**
     * Get the specified configuration value.
     *
     * @param string $name
     * @param string $key
     * @param mixed  $default
     *
     * @return string
     */
    function addon_spec($name, $key)
    {
        return call_user_func_array([addon($name), 'spec'], array_slice(func_get_args(), 1));
    }
}

if (!function_exists('spec')) {
    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function spec($key = null, $default = null)
    {
        $repository = app('specs');

        if (func_num_args() == 0) {
            return $repository;
        }

        return app('specs')->get($key, $default);
    }
}
