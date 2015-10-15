<?php

use LaravelPlus\Extension\Specs\Translator;

class TranslatorTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $command = new Translator();

        Assert::isInstanceOf(Translator::class, $command);
    }
}
