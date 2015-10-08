<?php

namespace LaravelPlus\Extension\Generators;

class ClassName
{
    /**
     * Class name.
     *
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = trim($name, '\\');
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name.'::class';
    }
}
