<?php

namespace LaravelPlus\Extension\Specs;

class Factory
{
    /**
     * rule string or array.
     *
     * @param string $path
     *
     * @return LaravelPlus\Extension\Specs\InputSpec
     */
    public function make($path)
    {
        return new InputSpec(app('specs'), app('translator'), $path);
    }

    /**
     * Get the specified spec value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return LaravelPlus\Extension\Repository\NamespacedRepository|string
     */
    function get($key, $default = null)
    {
        return app('specs')->get($key, $default);
    }

    /**
     * @param string $namespace
     *
     * @return LaravelPlus\Extension\Specs\Translator
     */
    public function translator($namespace)
    {
        return new Translator(app('translator'), $namespace);
    }

    /**
     * @param string|LaravelPlus\Extension\Specs\InputSpec $pathOrSpec
     * @param array                                        $in
     *
     * @return LaravelPlus\Extension\Specs\InputModel
     */
    public function inputModel($pathOrSpec, array $in = null)
    {
        if (is_string($pathOrSpec)) {
            $spec = $this->make($pathOrSpec);
        }
        else {
            $spec = $pathOrSpec;
        }

        return new InputModel($spec, $in);
    }

    /**
     * @param string $id
     * @param string $path
     *
     * @return LaravelPlus\Extension\Specs\FormModel
     */
    public function formModel($id, $path)
    {
        return new FormModel($id, $this->make($path));
    }

}
