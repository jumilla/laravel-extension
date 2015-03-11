<?php

if (! function_exists('addon_name')) {
	/**
	 * @param  string  $class
	 * @return string|null
	 */
	function addon_name($class = null)
	{
		if (! $class) {
			list(, $caller) = debug_backtrace(false, 2);

			if (! isset($caller['class'])) {
				return null;
			}

			$class = $caller['class'];
		}

		foreach (\LaravelPlus\Extension\Addons\AddonManager::addons() as $addon) {
			if (starts_with($class, $addon->config('namespace'))) {
				return $addon->name;
			}
		}

		return null;
	}
}

if (! function_exists('addon_namespace')) {
	/**
	 * @param  string  $class
	 * @return string|null
	 */
	function addon_namespace($class = null)
	{
		if (! $class) {
			list(, $caller) = debug_backtrace(false, 2);

			if (! isset($caller['class'])) {
				return '';
			}

			$class = $caller['class'];
		}

		$name = addon_name($class);

		return $name ? $name . '::' : '';
	}
}
