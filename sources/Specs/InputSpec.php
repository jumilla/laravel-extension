<?php

namespace LaravelPlus\Extension\Specs;

use LaravelPlus\Extension\Repository\NamespacedRepository;
use Illuminate\Contracts\Translation\Translator as TranslatorInterface;
use InvalidArgumentException;

class InputSpec
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var \LaravelPlus\Extension\Specs\Translator
     */
    protected $translator;

    /**
     * @param \LaravelPlus\Extension\Repository\NamespacedRepository $specs
     * @param \Illuminate\Contracts\Translation\Translator $translator
     * @param string $path
     */
    public function __construct(NamespacedRepository $specs, TranslatorInterface $translator, $path)
    {
        $rules = $specs->get($path);

        if (is_null($rules)) {
            throw new InvalidArgumentException("spec '$path' is not found");
        }
        if (!is_array($rules)) {
            throw new InvalidArgumentException('$rules must array in path '.$path);
        }
        if (!$translator->has($path)) {
            throw new InvalidArgumentException("translate '$path' is not found");
        }

        if (strpos($path, '::') !== false) {
            list($namespace, $path) = explode('::', $path, 2);
        } else {
            $namespace = '';
        }

        $this->path = $path;
        $this->namespace = $namespace;
        $this->rules = $rules;
        $this->translator = new Translator($translator, $namespace);

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

        return $this->translator->get($path, []);
    }

    /**
     * @return array
     */
    public function labels()
    {
        $path = $this->path.'.attributes';

        return $this->translator->get($path, []);
    }

    /**
     * @return array
     */
    public function values()
    {
        $path = $this->path.'.values';

        return $this->translator->get($path, []);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function required($name)
    {
        return $this->hasRule($this->rules[$name], 'required');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function label($name)
    {
        $path = $this->path.'.attributes.'.$name;

        return $this->translator->get($path);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function helptext($name)
    {
        $path = $this->path.'.helptexts.'.$name;

        return $this->translator->get($path, '');
    }

    /**
     * @param mixed  $ruleOrRules
     * @param string $name
     *
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
     * @param array  $rules
     * @param string $name
     *
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
     */
    private function resolveSpecReferences()
    {
        foreach ($this->rules as &$value) {
            if (strpos($value, '@') !== false) {
                list(, $key) = explode('@', $value, 2);

                $rule = app('specs')->get($this->fullkey('vocabulary.'.$key), null);

                if (empty($rule)) {
                    throw new InvalidArgumentException('specs/vocabulary '.$key.' not found.');
                }

                $value = $rule;
            }
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function fullkey($key)
    {
        return $this->namespace ? $this->namespace.'::'.$key : $key;
    }
}
