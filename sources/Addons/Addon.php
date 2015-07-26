<?php

namespace LaravelPlus\Extension\Addons;

use \RuntimeException;
use Illuminate\Config\Repository;
use LaravelPlus\Extension\Application;
use LaravelPlus\Extension\Repository\ConfigLoader;

class Addon
{
    /**
     * @param string $path
     * @return static
     */
    public static function create($path)
    {
        $pathComponents = explode('/', $path);

        $name = $pathComponents[count($pathComponents) - 1];

        $addonConfig = static::loadConfig($path, $name);

        $config = ConfigLoader::load($path.'/'.array_get($addonConfig, 'paths.config', 'config'));

        $config->set('addon', $addonConfig);

        return new static($name, $path, $config);
    }

    /**
     * @param string $path
     * @return array
     */
    protected static function loadConfig($path, $name)
    {
        if (file_exists($path.'/addon.php')) {
            $config = require $path.'/addon.php';
        }
        else if (file_exists($path.'/addon.json')) {
            $config = json_decode(file_get_contents($path.'/addon.json'), true);

            if ($config === null) {
                throw new RuntimeException("Invalid json format at '$path/addon.json'.");
            }
        }
        // compatible v4 addon
        else if (file_exists($path.'/config/addon.php')) {
            $config = require $path.'/config/addon.php';
        }
        else {
            throw new RuntimeException("No such config file for addon '$name', need 'addon.php' or 'addon.json'.");
        }

        return $config;
    }

    /**
     * @param string $path
     * @return static
     */
    public static function createApp()
    {
        $path = app_path();

        $config = new Repository([
            'addon' => [
                'namespace' => Application::getNamespace(),
            ],
        ]);

        return new static('app', $path, $config);
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @param string $name
     * @param string $path
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct($name, $path, Repository $config)
    {
        $this->name = $name;
        $this->path = $path;
        $this->config = $config;
    }

    /**
     * get name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * get fullpath.
     *
     * @return string
     */
    public function path($path = null)
    {
        if (func_num_args() == 0) {
            return $this->path;
        } else {
            return $this->path.'/'.$path;
        }
    }

    /**
     * get relative path.
     *
     * @return string
     */
    public function relativePath()
    {
        return substr($this->path, strlen(base_path()) + 1);
    }

    /**
     * get version.
     *
     * @return int
     */
    public function version()
    {
        return $this->config('addon.version', 4);
    }

    /**
     * get PHP namespace.
     *
     * @return string
     */
    public function phpNamespace()
    {
        return trim($this->config('addon.namespace', ''), '\\');
    }

    /**
     * get config value.
     *
     * @param  string $name
     * @param  mixed  $default
     * @return int
     */
    public function config($name, $default = null)
    {
        return $this->config->get($name, $default);
    }

    /**
     * register addon.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function register($app)
    {
        $version = $this->version();
        if ($version == 4) {
            $this->registerV4($app);
        } elseif ($version == 5) {
            $this->registerV5($app);
        } else {
            throw new \Exception($version.': Illigal addon version.');
        }
    }

    /**
     * register addon version 4.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    private function registerV4($app)
    {
        $this->config['paths'] = [
            'assets' => 'assets',
            'lang' => 'lang',
            'migrations' => 'migrations',
            'seeds' => 'seeds',
            'specs' => 'specs',
            'views' => 'views',
        ];

        // regist service providers
        $providers = $this->config('addon.providers', []);
        foreach ($providers as $provider) {
            if (!starts_with($provider, '\\')) {
                $provider = sprintf('%s\%s', $this->phpNamespace(), $provider);
            }

            $app->register($provider);
        }
    }

    /**
     * register addon version 5.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    private function registerV5($app)
    {
        // regist service providers
        $providers = $this->config('addon.providers', []);
        foreach ($providers as $provider) {
            $app->register($provider);
        }
    }

    /**
     * boot addon.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function boot($app)
    {
        $version = $this->version();
        if ($version == 4) {
            $this->bootV4($app);
        } elseif ($version == 5) {
            $this->bootV5($app);
        } else {
            throw new \Exception($version.': Illigal addon version.');
        }
    }

    /**
     * boot addon version 4.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    private function bootV4($app)
    {
        $filenames = $this->config('files');

        $files = [];

        if ($filenames !== null) {
            foreach ($filenames as $filename) {
                $files[] = $addon->path.'/'.$filename;
            }
        } else {
            // load *.php on addon's root directory
            foreach ($app['files']->files($this->path) as $file) {
                if (ends_with($file, '.php')) {
                    require $file;
                }
            }
        }

        $this->loadFiles($files);
    }

    /**
     * boot addon version 5.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    private function bootV5($app)
    {
        $filenames = $this->config('files');

        $files = [];

        if ($filenames !== null) {
            foreach ($filenames as $filename) {
                $files[] = $addon->path.'/'.$filename;
            }
        }

        $this->loadFiles($files);
    }

    /**
     * load addon initial script files.
     *
     * @param  array  $files
     * @return void
     */
    private function loadFiles(array $files)
    {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                $message = "Warning: PHP Script '$file' is nothing.";
                info($message);
                echo $message;
            }

            include $file;
        }
    }
}
