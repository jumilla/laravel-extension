<?php

if (! function_exists('addon')) {
	/**
	 * @param  string $name
	 * @return array
	 */
	function addon($name)
	{
		return \LaravelPlus\Extension\Application::getAddon($name);
	}
}
