<?php

namespace LaravelPlus\Extension\Generators;

use InvalidArgumentException;

class FileGenerator
{
    /**
     * @var \Illuminate\Contracts\Filesystem
     */
    protected $outbox;

    /**
     * @var \Illuminate\Contracts\Filesystem
     */
    protected $stubbox;

    /**
     * @var \stdClass
     */
    protected $context;

    /**
     * @param string $outbox_path
     * @param string $stubbox_path
     * @return static
     */
    public static function make($outbox_path, $stubbox_path)
    {
        $outbox = app('filesystem')->createLocalDriver([
            'root' => $outbox_path,
        ]);

        $stubbox = app('filesystem')->createLocalDriver([
            'root' => $stubbox_path,
        ]);

        $context = (object) [
            'outbox_root' => $outbox_path,
            'stubbox_root' => $stubbox_path,
            'directory' => null,
            'file' => null,
        ];

        return new static($outbox, $stubbox, $context);
    }

    /**
     * @param \Illuminate\Contracts\Filesystem $outbox
     * @param \Illuminate\Contracts\Filesystem $stubbox
     * @param \stdClass $context
     */
    public function __construct($outbox, $stubbox, $context)
    {
        $this->outbox = $outbox;
        $this->stubbox = $stubbox;
        $this->context = $context;
    }

    /**
     * @param string $path
     * @param callable $callable
     * @return static
     */
    public function directory($path, callable $callable = null)
    {
        $directory_path = $this->makePath($path);

        $this->outbox->makeDirectory($directory_path);

        $context = clone($this->context);
        $context->directory = $directory_path;

        $sub = new static($this->outbox, $this->stubbox, $context);

        if ($callable) {
            call_user_func($callable, $sub);
        }

        return $sub;
    }

    public function sourceDirectory($path)
    {
        foreach ($this->stubbox->allFiles($this->makePath($path)) as $stubbox_path) {
            if ($this->context->directory) {
                $outbox_path = substr($stubbox_path, strlen($this->context->directory) + 1);
            } else {
                $outbox_path = $stubbox_path;
            }
            $this->directory(dirname($outbox_path))->file(basename($outbox_path))->source($stubbox_path);
        }
    }

    public function templateDirectory($path, array $arguments = [])
    {
        foreach ($this->stubbox->allFiles($this->makePath($path)) as $stubbox_path) {
            if ($this->context->directory) {
                $outbox_path = substr($stubbox_path, strlen($this->context->directory) + 1);
            } else {
                $outbox_path = $stubbox_path;
            }
            $this->directory(dirname($outbox_path))->file(basename($outbox_path))->template($stubbox_path, $arguments);
        }
    }

    public function keepDirectory($path, $file = '.gitkeep')
    {
        $this->directory($path)->gitKeepFile($file);
    }

    public function file($path)
    {
        $this->context->file = $this->makePath($path);

        return $this;
    }

    public function touch()
    {
        $this->outbox->put($this->context->file, '');
    }

    public function text($content = null)
    {
        $this->outbox->put($this->context->file, $content);
    }

    public function json(array $data)
    {
        $this->outbox->put($this->context->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function source($stub_path)
    {
        $content = $this->stubbox->get($stub_path);

        if ($content === false) {
            throw new InvalidArgumentException("File '$stub_path' is not found.");
        }

        $this->outbox->put($this->context->file, $content);
    }

    public function template($stub_path, array $arguments = [])
    {
        $content = $this->stubbox->get($stub_path);

        if ($content === false) {
            throw new InvalidArgumentException("File '$stub_path' is not found.");
        }

        foreach ($arguments as $name => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $content = preg_replace('/\{\s*\$'.$name.'\s*\}/', $value, $content);
        }

        $this->outbox->put($this->context->file, $content);
    }

    public function gitKeepFile($path = '.gitkeep')
    {
        $this->file($path)->text();
    }

    public function phpBlankFile($path)
    {
        $this->file($path)->text('<?php'.PHP_EOL.PHP_EOL);
    }

    public function phpConfigFile($path, array $config = [])
    {
        $this->file($path)->text(PhpConfigGenerator::generateText($config));
    }

    public function phpSourceFile($path, $source, $namespace = null)
    {
        if ($namespace) {
            $namespace = "namespace {$namespace};";
        }

        $this->file($path)->text('<?php'.PHP_EOL.PHP_EOL.$namespace.PHP_EOL.PHP_EOL.$source.PHP_EOL);
    }

    public function sourceFile($path)
    {
        $this->file($path)->source($this->makePath($path));
    }

    public function templateFile($path, array $arguments = [])
    {
        $this->file($path)->template($this->makePath($path), $arguments);
    }

    protected function makePath($path)
    {
        return $this->context->directory ? $this->context->directory.'/'.$path : $path;
    }
}
