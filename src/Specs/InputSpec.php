<?php namespace Jumilla\LaravelExtension\Specs;

class InputSpec {

	/* @param rule string or array */
	public static function make($path)
	{
		return new static($path);
	}

	/* @param string */
	protected $namespace;

	/* @param string */
	protected $path;

	/* @param array */
	protected $rules = [];

	/* @param path string */
	public function __construct($path)
	{
		$rules = app('specs')->get($path);

		if (is_null($rules))
			throw new \InvalidArgumentException("spec '$path' is not found");

		if (!is_array($rules))
			throw new \InvalidArgumentException('$rules must array in path '.$path);

		if (strpos($path, '::') !== false) {
			list($namespace, $path) = explode('::', $path, 2);
		}
		$this->path = $path;
		$this->namespace = $namespace;
		$this->rules = $rules;

		$this->resolveSpecReferences();
	}

	public function attributes()
	{
		return array_keys($this->rules);
	}

	public function rules()
	{
		return $this->rules;
	}

	public function ruleMessages()
	{
		$path = $this->path.'.rules';
		return Translator::make($this->namespace)->get($path);
	}

	public function labels()
	{
		$path = $this->path.'.attributes';
		return Translator::make($this->namespace)->get($path);
	}

	public function values()
	{
		$path = $this->path.'.values';
		return Translator::make($this->namespace)->get($path);
	}

	public function required($name)
	{
		return $this->hasRule($this->rules[$name], 'required');
	}

	public function label($name)
	{
		$path = $this->path.'.attributes.'.$name;
		return Translator::make($this->namespace)->get($path);
	}

	public function helptext($name)
	{
		$path = $this->path.'.helptexts.'.$name;
		return Translator::make($this->namespace)->get($path, '');
	}

	protected function hasRule($ruleOrRules, $name)
	{
		if (is_string($ruleOrRules)) {
			return $this->hasRuleInArray(explode('|', $ruleOrRules), 'required');
		}
		else if (is_array($ruleOrRules)) {
			return $this->hasRuleInArray($ruleOrRules, 'required');
		}
		else {
			return false;
		}
	}

	private function hasRuleInArray(array $rules, $name)
	{
		foreach ($rules as $rule) {
			if ($rule == $name)
				return true;
		}

		return false;
	}

	private function resolveSpecReferences()
	{
		foreach ($this->rules as $key => &$value) {
			if (strpos($value, '@') !== false) {
				list(, $vocabularyPath) = explode('@', $value, 2);

				$rule = app('specs')->get($this->fullpath('vocabulary.'.$vocabularyPath), null);

				if (empty($rule))
					throw new \InvalidArgumentException('specs/vocabulary '.$vocabularyPath.' not found.');

				$value = $rule;
			}
		}
	}

	private function fullpath($path)
	{
		return $this->namespace ? $this->namespace.'::'.$path : $path;
	}

}
