<?php

namespace LaravelPlus\Extension\Specs;

class FormModel
{
    /**
     * @param string $id
     * @param string $path
     * @return static
     */
    public static function make($id, $path)
    {
        return new static($id, $path);
    }

    /**
     *	Form name for HTML Element.
     *	@var string
     */
    protected $id;

    /**
     *	Form spec.
     *	@var \LaravelPlus\Extension\Specs\InputSpec
     */
    protected $spec;

    /**
     * @param string $id
     * @param string $path
     */
    public function __construct($id, $path)
    {
        $this->id = $id;
        $this->spec = new InputSpec($path);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $fieldName
     * @return string
     */
    public function fieldId($fieldName)
    {
        return $this->id.'-'.$fieldName;
    }

    /**
     * @param string $method
     * @return array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->spec, $method], $arguments);
    }
}
