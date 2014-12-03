<?php namespace LaravelPlus\Extension\Specs;

class FormModel {

	/**
	 * Form name for HTML Element
	 * @var string
	 */
	protected $id;

	/**
	 * Form spec
	 * @var \LaravelPlus\Extension\Specs\InputSpec
	 */
	protected $spec;

	/**
	 * @param  string $id
	 * @param  string $specPath
	 * @return \LaravelPlus\Extension\Specs\FormModel
	 */
	public static function make($id, $specPath)
	{
		return new static($id, $specPath);
	}

	/**
	 * @param  string $id
	 * @param  string $specPath
	 * @return void
	 */
	public function __construct($id, $specPath)
	{
		$this->id = $id;
		$this->spec = new InputSpec($specPath);
	}

	/**
	 * @return string
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * @param  string $fieldName
	 * @return string
	 */
	public function fieldId($fieldName)
	{
		return $this->id.'-'.$fieldName;
	}

	/**
	 * @param  string $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array([$this->spec, $method], $parameters);
	}

}
