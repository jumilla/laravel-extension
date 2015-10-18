<?php

namespace LaravelPlus\Extension\Specs;

use Symfony\Component\Translation\TranslatorInterface;

class Translator
{
    /**
     * @var Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     * @param string $namespace
     */
    public function __construct(TranslatorInterface $translator, $namespace = '')
    {
        $this->translator = $translator;
        $this->namespace = $namespace;
    }

    /**
     * Determine if a translation exists.
     *
     * @param  string  $key
     * @param  string  $locale
     * @return bool
     */
    public function has($key, $locale = null)
    {
        return $this->translator->has($this->fullkey($key));
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = false)
    {
        $value = $this->translate($this->fullkey($key), $default);

        if (is_string($value)) {
            $value = $this->resolve($value);
        } elseif (is_array($value)) {
            foreach ($value as &$subValue) {
                $subValue = $this->resolve($subValue);
            }
        }

        return $value;
    }

    /**
     * @param string $string
     * @param string $default
     *
     * @return string
     */
    public function resolve($string, $default = false)
    {
        if (strpos($string, '@') !== false) {
            list(, $key) = explode('@', $string, 2);

            $string = $this->translate($this->fullkey('vocabulary.'.$key), $default);
        }

        return $string;
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

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function translate($key, $default = false)
    {
        $string = $this->translator->get($key);

        if ($default !== false) {
            if ($string == $key) {
                return $default;
            }
        }

        return $string;
    }
}
