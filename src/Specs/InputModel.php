<?php namespace Jumilla\LaravelExtension\Specs;

class InputModel {

	private $spec;

	private $in;

	private $validator;

	public static function make($spec, $in = null)
	{
		$instance = new static($spec, $in);

		return $instance;
	}

	public function __construct($spec, $in = null)
	{
		if (is_string($spec)) {
			$spec = InputSpec::make($spec);
		}

		$this->spec = $spec;
		$this->in = $in ?: $this->getInput();
		$this->validator = \Validator::make($this->in, $this->spec->rules());
	}

	public function getInput()
	{
		return \Input::only($this->spec->attributes());
	}

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->in[$key];
	}

	/**
	 * Dynamically set attributes on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->in[$key] = $value;
	}

	public function passes()
	{
		return $this->validator->passes();
	}

	public function fails()
	{
		return $this->validator->fails();
	}

	public function errors()
	{
		return $this->validator->errors();
	}

	public function validator()
	{
		return $this->validator;
	}

}
