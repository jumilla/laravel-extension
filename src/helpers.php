<?php

if (! function_exists('addon')) {
	/**
	 * @param  string  $name Addon name.
	 * @return \LaravelPlus\Extension\Addons\Addon
	 */
	function addon($name)
	{
		return \LaravelPlus\Extension\Application::getAddon($name);
	}
}

if (! function_exists('addon_config')) {
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

if (! function_exists('addon_trans')) {
	/**
	 * @param  string  $name Addon name.
	 * @param  ...
	 * @return string
	 */
	function addon_trans()
	{
		$args = func_get_args();

		$name = array_shift($args);
		$args[0] = $name . '::' . $args[0];

		return call_user_func_array('trans', $args);
	}
}

if (! function_exists('addon_trans_choice')) {
	/**
	 * @param  string  $name Addon name.
	 * @param  ...
	 * @return string
	 */
	function addon_trans_choice()
	{
		$args = func_get_args();

		$name = array_shift($args);
		$args[0] = $name . '::' . $args[0];

		return call_user_func_array('trans_choice', $args);
	}
}

if (! function_exists('addon_spec')) {
	/**
	 * @param  string  $name Addon name.
	 * @param  ...
	 * @return string
	 */
	function addon_spec()
	{
		$args = func_get_args();

		$name = array_shift($args);
		$args[0] = $name . '::' . $args[0];

		return call_user_func_array([app('specs'), 'get'], $args);
	}
}

if (! function_exists('addon_view')) {
	/**
	 * @param  string  $name Addon name.
	 * @param  ...
	 * @return \Illuminate\View\View
	 */
	function addon_view()
	{
		$args = func_get_args();

		$name = array_shift($args);
		$args[0] = $name . '::' . $args[0];

		return call_user_func_array('view', $args);
	}
}
