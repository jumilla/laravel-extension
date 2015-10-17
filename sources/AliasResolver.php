<?php

namespace LaravelPlus\Extension;

use LaravelPlus\Extension\Addons\Addon;
use Illuminate\Config\Repository;
use Illuminate\Console\AppNamespaceDetectorTrait;

class AliasResolver
{
    use AppNamespaceDetectorTrait;

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @param string $app_path
     * @param array $addons
     * @param array $aliases
     */
    public static function register($app_path, array $addons, array $aliases)
    {
        static::$instance = new static($app_path, $addons, $aliases);

        // TODO check addon configuration

        spl_autoload_register([static::$instance, 'load'], true, false);
    }

    /**
     */
    public static function unregister()
    {
        if (static::$instance) {
            spl_autoload_unregister([static::$instance, 'load']);
        }
    }

    /**
     * @var array
     */
    protected $addons;

    /**
     * @var array
     */
    protected $globalClassAliases;

    /**
     * The constructor.
     *
     * @param string $app_path
     * @param array $addons
     * @param array $aliases
     */
    public function __construct($app_path, array $addons, array $aliases)
    {
        $this->addons = array_merge([$this->makeAppAddon($app_path)], $addons);
        $this->globalClassAliases = $aliases;
    }

    /**
     * Make addon instance for application namespace.
     *
     * @param string $path
     *
     * @return static
     */
    protected function makeAppAddon($path)
    {
        $config = new Repository([
            'addon' => [
                'namespace' => trim($this->getAppNamespace(), '\\'),
            ],
        ]);

        return new Addon('app', $path, $config);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function load($className)
    {
        foreach ($this->addons as $addon) {
            $namespace = $addon->phpNamespace();

            // 名前空間のないパッケージはエイリアス解決をする必要がない
            if (empty($namespace)) {
                continue;
            }

            $namespacePrefix = $namespace.'\\';
            $includesGlobalAliases = $addon->config('addon.includes_global_aliases', true);
            $addonAliases = $addon->config('addon.aliases', []);

            // アドオンの名前空間下のクラスでないなら
            if (!starts_with($className, $namespacePrefix)) {
                continue;
            }

            // クラス名を取り出す
            $parts = explode('\\', $className);
            $relativeClassName = $parts[count($parts) - 1];

            // グローバルなエイリアスかチェックする
            if ($includesGlobalAliases) {
                if (isset($this->globalClassAliases[$relativeClassName])) {
                    $originalClassName = $this->globalClassAliases[$relativeClassName];
                    class_alias($originalClassName, $className);

                    return true;
                }
            }

            // パッケージ固有のエイリアスかチェックする
            if ($addonAliases) {
                if (isset($addonAliases[$relativeClassName])) {
                    $originalClassName = $addonAliases[$relativeClassName];
                    class_alias($originalClassName, $className);

                    return true;
                }
            }
        }

        return false;
    }
}
