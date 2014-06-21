<?php namespace Jumilla\LaravelExtension\Specs;

class InputSpec {

	protected $rules = [];

	protected $namespace;

	public static function make($rules)
	{
		return new static($rules);
	}

	public function __construct($rules, $namespace = null)
	{
		if (is_string($rules)) {
			if (strpos($rules, '::') !== false) {
				list($namespace, ) = explode('::', $rules);
			}
			$rules = app('specs')->get($rules);
		}

		if (!is_array($rules))
			throw new \InvalidArgumentException('$rules must array');

		$this->rules = $rules;
		$this->namespace = $namespace;

		$this->resolveReferences();
	}

	public function attributes()
	{
		return array_keys($this->rules);
	}

	public function rules()
	{
		return $this->rules;
	}

	private function resolveReferences()
	{
		foreach ($this->rules as $key => &$value) {
			if (strpos($value, '@') !== false) {
				list(, $vocabularyName) = explode('@', $value);

				$prefix = $this->namespace ? $this->namespace.'::' : '';
				$rule = app('specs')->get($prefix.'vocaburary.'.$vocabularyName, null);

				if (empty($rule))
					throw new \InvalidArgumentException('vocaburary '.$value.' not found.');

				$value = $rule;
			}
		}
	}

}
