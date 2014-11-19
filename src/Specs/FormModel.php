<?php namespace LaravelPlus\Extension\Specs;

class FormModel {

	/**
	 *	Form name for HTML Element
	 *	@param string
	 */
	protected $id;

	/**
	 *	Form spec
	 *	@param InputSpec
	 */
	protected $spec;

	public static function make($id, $spec)
	{
		return new static($id, $spec);
	}

	public function __construct($id, $spec)
	{
		$this->id = $id;
		$this->spec = new InputSpec($spec);
	}

	public function id()
	{
		return $this->id;
	}

	public function fieldId($fieldName)
	{
		return $this->id.'-'.$fieldName;
	}

	public function __call($method, $parameters)
	{
		return call_user_func_array([$this->spec, $method], $parameters);
	}

}
