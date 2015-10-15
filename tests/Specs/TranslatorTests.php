<?php

use LaravelPlus\Extension\Specs\Translator;

class TranslatorTests extends TestCase
{
    public function test_withNoParameter()
    {
        $command = new Translator('foo');

        Assert::isInstanceOf(Translator::class, $command);
    }
}
