<?php namespace Jumilla\LaravelExtension;

class AliasResolver {

	private static $instance;

	public static function register($plugins, $aliases)
	{
		static::$instance = new static($plugins, $aliases);

		// TODO check plugin configuration

		spl_autoload_register([static::$instance, 'load'], true, false);
	}

	public static function unregister()
	{
		if (static::$instance) {
			spl_autoload_unregister([static::$instance, 'load']);
		}
	}

	private $plugins;

	private $globalClassAliases;

	public function __construct($plugins, $aliases)
	{
		$this->plugins = $plugins;
		$this->globalClassAliases = $aliases;
	}

	public function load($className)
	{
		foreach ($this->plugins as $plugin) {
			$namespace = $plugin->config('namespace');

			// 名前空間のないパッケージはエイリアス解決をする必要がない
			if (empty($namespace))
				continue;

			$namespacePrefix = $namespace.'\\';
			$includesGlobalAliases = $plugin->config('includes_global_aliases', true);
			$pluginAliases = $plugin->config('aliases', []);

			// プラグインの名前空間下のクラスでないなら
			if (!starts_with($className, $namespacePrefix))
				continue;

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
			if ($pluginAliases) {
				if (isset($pluginAliases[$relativeClassName])) {
					$originalClassName = $pluginAliases[$relativeClassName];
					class_alias($originalClassName, $className);
					return true;
				}
			}
		}

		return false;
	}

}
