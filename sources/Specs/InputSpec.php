<?php

namespace LaravelPlus\Extension\Specs;

class InputSpec
{
    /**
     * rule string or array.
     *
     * @param string $path
     * @return static
     */
    public static function make($path)
    {
        return new static($path);
    }

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param string
     */
    protected $path;

    /**
     * @param array
     */
    protected $rules = [];

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $rules = app('specs')->get($path);

        if (is_null($rules)) {
            throw new \InvalidArgumentException("spec '$path' is not found");
        }

        if (!is_array($rules)) {
            throw new \InvalidArgumentException('$rules must array in path '.$path);
        }

        if (!app('translator')->has($path)) {
            throw new \InvalidArgumentException("translate '$path' is not found");
        }

        if (strpos($path, '::') !== false) {
            list($namespace, $path) = explode('::', $path, 2);
        } else {
            $namespace = '';
        }
        $this->path = $path;
        $this->namespace = $namespace;
        $this->rules = $rules;

        $this->resolveSpecReferences();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array_keys($this->rules);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function ruleMessages()
    {
        $path = $this->path.'.rules';

        return Translator::make($this->namespace)->get($path, []);
    }

    /**
     * @return array
     */
    public function labels()
    {
        $path = $this->path.'.attributes';

        return Translator::make($this->namespace)->get($path, []);
    }

    /**
     * @return array
     */
    public function values()
    {
        $path = $this->path.'.values';

        return Translator::make($this->namespace)->get($path, []);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function required($name)
    {
        return $this->hasRule($this->rules[$name], 'required');
    }

    /**
     * @param string $name
     * @return string
     */
    public function label($name)
    {
        $path = $this->path.'.attributes.'.$name;

        return Translator::make($this->namespace)->get($path);
    }

    /**
     * @param string $name
     * @return string
     */
    public function helptext($name)
    {
        $path = $this->path.'.helptexts.'.$name;

        return Translator::make($this->namespace)->get($path, '');
    }

    /**
     * @param mixed $ruleOrRules
     * @param string $name
     * @return bool
     */
    protected function hasRule($ruleOrRules, $name)
    {
        if (is_string($ruleOrRules)) {
            return $this->hasRuleInArray(explode('|', $ruleOrRules), 'required');
        } elseif (is_array($ruleOrRules)) {
            return $this->hasRuleInArray($ruleOrRules, 'required');
        } else {
            return false;
        }
    }

    /**
     * @param array $rules
     * @param string $name
     * @return bool
     */
    private function hasRuleInArray(array $rules, $name)
    {
        foreach ($rules as $rule) {
            if ($rule == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    private function resolveSpecReferences()
    {
        foreach ($this->rules as $key => &$value) {
            if (strpos($value, '@') !== false) {
                list(, $vocabularyPath) = explode('@', $value, 2);

                $rule = app('specs')->get($this->fullpath('vocabulary.'.$vocabularyPath), null);

                if (empty($rule)) {
                    throw new \InvalidArgumentException('specs/vocabulary '.$vocabularyPath.' not found.');
                }

                $value = $rule;
            }
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function fullpath($path)
    {
        return $this->namespace ? $this->namespace.'::'.$path : $path;
    }
}
