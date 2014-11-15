<?php namespace Jumilla\LaravelExtension;

class AliasResolver {

	private static $instance;

	public static function register($addons, $aliases)
	{
		static::$instance = new static($addons, $aliases);

		// TODO check addon configuration

		spl_autoload_register([static::$instance, 'load'], true, false);
	}

	public static function unregister()
	{
		if (static::$instance) {
			spl_autoload_unregister([static::$instance, 'load']);
		}
	}

	private $addons;

	private $globalClassAliases;

	public function __construct(array $addons, array $aliases)
	{
		$this->addons = [
			Addon::createApp(),
		];
		$this->addons += $addons;
		$this->globalClassAliases = $aliases;
	}

	public function load($className)
	{
		foreach ($this->addons as $addon) {
			$namespace = $addon->config('namespace');

			// 名前空間のないパッケージはエイリアス解決をする必要がない
			if (empty($namespace))
				continue;

			$namespacePrefix = $namespace.'\\';
			$includesGlobalAliases = $addon->config('includes_global_aliases', true);
			$addonAliases = $addon->config('aliases', []);

			// アドオンの名前空間下のクラスでないなら
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
