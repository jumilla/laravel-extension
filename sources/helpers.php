<?php

if (!function_exists('addon_name')) {
    /**
     * @param  string  $class
     * @return string|null
     */
    function addon_name($class = null)
    {
        if (!$class) {
            list(, $caller) = debug_backtrace(false, 2);

            if (!isset($caller['class'])) {
                return;
            }

            $class = $caller['class'];
        }

        foreach (\LaravelPlus\Extension\Application::getAddons() as $addon) {
            if (starts_with($class, $addon->config('addon.namespace'))) {
                return $addon->name();
            }
        }

        return;
    }
}

if (!function_exists('addon_namespace')) {
    /**
     * @param  string  $class
     * @return string|null
     */
    function addon_namespace($class = null)
    {
        if (!$class) {
            list(, $caller) = debug_backtrace(false, 2);

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
     * @param  string  $name Addon name.
     * @return \LaravelPlus\Extension\Addons\Addon
     */
    function addon($name)
    {
        return \LaravelPlus\Extension\Application::getAddon($name);
    }
}

if (!function_exists('addon_path')) {
    /**
     * @param  string  $name Addon name.
     * @param  string|null  $path
     * @return mixed
     */
    function addon_path($name, $path = null)
    {
        return addon($name)->path($path);
    }
}

if (!function_exists('addon_config')) {
    /**
     * @param  string  $name Addon name.
     * @param  string  $key
     * @param  mixed   $value
     * @return mixed
     */
    function addon_config($name, $key, $value = null)
    {
        return addon($name)->config($key, $value);
    }
}

if (!function_exists('addon_trans')) {
    /**
     * @param  string  $name Addon name.
     * @param  ...
     * @return string
     */
    function addon_trans()
    {
        $args = func_get_args();

        $name = array_shift($args);
        $args[0] = $name.'::'.$args[0];

        return call_user_func_array('trans', $args);
    }
}

if (!function_exists('addon_trans_choice')) {
    /**
     * @param  string  $name Addon name.
     * @param  ...
     * @return string
     */
    function addon_trans_choice()
    {
        $args = func_get_args();

        $name = array_shift($args);
        $args[0] = $name.'::'.$args[0];

        return call_user_func_array('trans_choice', $args);
    }
}

if (!function_exists('addon_spec')) {
    /**
     * @param  string  $name Addon name.
     * @param  ...
     * @return string
     */
    function addon_spec()
    {
        $args = func_get_args();

        $name = array_shift($args);
        $args[0] = $name.'::'.$args[0];

        return call_user_func_array([app('specs'), 'get'], $args);
    }
}

if (!function_exists('addon_view')) {
    /**
     * @param  string  $name Addon name.
     * @param  ...
     * @return \Illuminate\View\View
     */
    function addon_view()
    {
        $args = func_get_args();

        $name = array_shift($args);
        $args[0] = $name.'::'.$args[0];

        return call_user_func_array('view', $args);
    }
}
