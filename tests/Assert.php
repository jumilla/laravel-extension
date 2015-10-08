<?php

final class Assert
{
    public static function __callStatic($method, array $arguments)
    {
        if (starts_with($method, 'is')) {
            $method = substr($method, 2);
        }

        $method = 'assert'.ucfirst($method);

        call_user_func_array([PHPUnit_Framework_Assert::class, $method], $arguments);
    }

    public static function containsAll(array $excepts, array $provides)
    {
        foreach ($excepts as $value) {
            static::contains($value, $provides);
        }
    }

    public static function failed($message)
    {
        static::equals(null, $message);
    }
}
