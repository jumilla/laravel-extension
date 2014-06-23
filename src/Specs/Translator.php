<?php namespace Jumilla\LaravelExtension\Specs;

class Translator {

	protected $namespace;

	public function make($namespace)
	{
		return new static($namespace);
	}

	public function __construct($namespace)
	{
		$this->namespace = $namespace;
	}

	public function get($path, $default = false)
	{
		$value = static::translate($path, $default);

		if (is_string($value)) {
			$value = $this->resolve($value);
		}
		else if (is_array($value)) {
			foreach ($value as &$subValue) {
				$subValue = $this->resolve($subValue);
			}
		}

		return $string;
	}

	public function resolve($string)
	{
		if (strpos($string, '@') !== false) {
			list(, $vocabularyPath) = explode('@', $string);

			$prefix = $this->namespace ? $this->namespace.'::' : '';
			$string = static::translate($prefix.'vocabulary.'.$vocabularyPath, $default);
		}

		return $string;
	}

	private static function translate($path, $default = false)
	{
		$string = app('translator')->get($path);

		if ($default !== false) {
			if ($string == $path)
				$string = $default;
		}

		return $string;
	}

}
