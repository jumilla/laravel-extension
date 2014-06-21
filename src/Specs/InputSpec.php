<?php namespace Jumilla\LaravelExtension\Specs;

class InputSpec {

	protected $rules = [];

	public static function make($rules = null)
	{
		if (is_string($rules)) {
			$rules = app('specs')->get($rules);
		}

		return new static($rules);
	}

	public function __construct($rules = null)
	{
		if ($rules)
			$this->rules = $rules;
	}

	public function attributes()
	{
		return array_keys($this->rules);
	}

	public function rules()
	{
		return $this->rules;
	}

}
