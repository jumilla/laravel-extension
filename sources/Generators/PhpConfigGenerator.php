<?php

namespace LaravelPlus\Extension\Generators;

class PhpConfigGenerator
{
    /**
     * @param array $config
     *
     * @return static
     */
    public static function generateText(array $config)
    {
        $instance = new static();

        return $instance->generate($config);
    }

    /**
     * @var string
     */
    protected $text;

    /**
     * @var int
     */
    protected $indent;

    /**
     * @param array $config
     *
     * @return string
     */
    public function generate(array $config)
    {
        $this->text = "<?php\n\nreturn [\n";
        $this->indent = 0;

        $this->generateArray($config);

        $this->writeLine('];');

        return $this->text;
    }

    /**
     * @param array $config
     */
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
            } elseif ($value instanceof ClassName) {
                if (is_string($key)) {
                    $this->writeLine(sprintf("'%s' => %s,", $key, (string) $value));
                } else {
                    $this->writeLine(sprintf('%s,', (string) $value));
                }
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

    /**
     * @param string $line
     */
    private function writeLine($line)
    {
        $this->text .= str_repeat(' ', $this->indent * 4);
        $this->text .= $line;
        $this->text .= "\n";
    }
}
