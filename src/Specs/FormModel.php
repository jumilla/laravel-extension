<?php namespace Jumilla\LaravelExtension\Specs;

class FormModel {

	/**
	 *	Form name for HTML Element
	 *	@param string
	 */
	protected $name;

	/**
	 *	Form spec
	 *	@param InputSpec
	 */
	protected $spec;

	public static function make($name, $spec)
	{
		return new static($name, $spec);
	}

	public function __construct($name, $spec)
	{
		$this->name = $name;
		$this->spec = new InputSpec($spec);
	}

	public function fieldId($fieldName)
	{
		return $this->name.'-'.$fieldName;
	}

	public function __call($method, $parameters)
	{
		return call_user_func_array([$this->spec, $method], $parameters);
	}

}
