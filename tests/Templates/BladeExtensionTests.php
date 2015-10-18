<?php

use LaravelPlus\Extension\Templates\BladeExtension;

class BladeExtensionTests extends TestCase
{
    use ConsoleCommandTrait;

    public function test_withNoParameter()
    {
        $extension = new BladeExtension();

        $closure = $extension->comment();
        Assert::same('<?php /* foo */ ?>', $closure('{# foo #}'));

        $closure = $extension->script();
        Assert::same('<?php  foo ; ?>', $closure('{@ foo @}'));
    }
}
