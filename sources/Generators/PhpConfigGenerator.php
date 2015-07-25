<?php

namespace LaravelPlus\Extension\Generators;

class PhpConfigGenerator
{
    public static function generateText(array $config)
    {
        $instance = new static;

        return $instance->generate($config);
    }

    public function generate(array $config)
    {
        $this->text = "<?php\n\nreturn [\n";
        $this->indent = 0;

        $this->generateArray($config);

        $this->writeLine('];');

        return $this->text;
    }

    private function generateArray(array $config)
    {
        ++$this->indent;

        foreach ($config as $key => $value) {
            if (is_null($value)) {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => %s,", $key, 'null'));
                } else {
                    $this->writeLine(sprintf('%s,', 'null'));
                }
            } elseif (is_bool($value)) {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => %s,", $key, $value ? 'true' : 'false'));
                } else {
                    $this->writeLine(sprintf('%s,', $value ? 'true' : 'false'));
                }
            } elseif (is_string($value)) {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => '%s',", $key, $value));
                } else {
                    $this->writeLine(sprintf("'%s',", $value));
                }
            } elseif (is_array($value)) {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => [", $key));
                } else {
                    $this->writeLine('[');
                }

                $this->generateArray($value);

                $this->writeLine('],');
            } else {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => %s,", $key, $value));
                } else {
                    $this->writeLine($value.',');
                }
            }
        }

        --$this->indent;
    }

    private function writeLine($line)
    {
        $this->text .= str_repeat("\t", $this->indent);
        $this->text .= $line;
        $this->text .= "\n";
    }
}