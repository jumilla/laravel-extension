<?php

namespace LaravelPlus\Extension\Specs;

class InputModel
{
    private $spec;

    private $in;

    private $validator;

    public static function make($spec, array $in = null)
    {
        $instance = new static($spec, $in);

        return $instance;
    }

    public function __construct($path, array $in = null)
    {
        if (is_string($path)) {
            $spec = InputSpec::make($path);
        }

        $this->spec = $spec;
        $this->in = $in ?: $this->getInput();
        $rules = $this->spec->rules();
        if (!is_array($rules)) {
            throw new \InvalidArgumentException('rule specs for "'.$path.'" must array.');
        }
        $ruleMessages = $this->spec->ruleMessages();
        if (!is_array($ruleMessages)) {
            throw new \InvalidArgumentException('rule translation for "'.$path.'" must array.');
        }
        $labels = $this->spec->labels();
        if (!is_array($labels)) {
            throw new \InvalidArgumentException('rule labels for "'.$path.'" must array.');
        }
        $this->validator = \Validator::make($this->in, $rules, $ruleMessages, $labels);
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
