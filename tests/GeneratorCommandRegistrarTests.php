<?php

use LaravelPlus\Extension\GeneratorCommandRegistrar;

class GeneratorCommandRegistrarTests extends TestCase
{
    public function test_withNoParameter()
    {
        $app = $this->createApplication();
        $registrar = new GeneratorCommandRegistrar($app);

        $registrar->register();

        Assert::isInstanceOf(GeneratorCommandRegistrar::class, $registrar);
    }
}
