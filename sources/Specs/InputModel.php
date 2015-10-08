<?php

namespace LaravelPlus\Extension\Specs;

use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;

class InputModel implements Arrayable
{
    /**
     * @var \LaravelPlus\Extension\Specs\InputSpec
     */
    protected $spec;

    /**
     * @var array
     */
    protected $in;

    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @param string|\LaravelPlus\Extension\Specs\InputSpec $spec
     * @param array                                         $in
     *
     * @return static
     */
    public static function make($spec, array $in = null)
    {
        $instance = new static($spec, $in);

        return $instance;
    }

    /**
     * @param string|\LaravelPlus\Extension\Specs\InputSpec $spec
     * @param array                                         $in
     */
    public function __construct($spec, array $in = null)
    {
        if (is_string($spec)) {
            $spec = InputSpec::make($spec);
        }

        $this->spec = $spec;
        $this->in = $in ?: $this->gatherInput();

        $rules = $this->spec->rules();
        if (!is_array($rules)) {
            throw new InvalidArgumentException("rule specs for '$path' must array.");
        }

        $ruleMessages = $this->spec->ruleMessages();
        if (!is_array($ruleMessages)) {
            throw new InvalidArgumentException("rule translation for '$path' must array.");
        }

        $labels = $this->spec->labels();
        if (!is_array($labels)) {
            throw new InvalidArgumentException("rule labels for '$path' must array.");
        }

        $this->validator = app('validator')->make($this->in, $rules, $ruleMessages, $labels);
    }

    /**
     * @return array
     */
    protected function gatherInput()
    {
        return app('request')->only($this->spec->attributes());
    }

    /**
     * @return bool
     */
    public function passes()
    {
        return $this->validator->passes();
    }

    /**
     * @return bool
     */
    public function fails()
    {
        return $this->validator->fails();
    }

    /**
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {
        return $this->validator;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->in[$key];
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->in[$key] = $value;
    }

    /**
     * @return array
     */
    public function input()
    {
        return $this->in;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->input();
    }
}
