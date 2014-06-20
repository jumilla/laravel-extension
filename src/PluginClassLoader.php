<?php namespace Jumilla\LaravelExtension;

class PluginClassLoader {

	private static $instance;

	public static function register($plugins)
	{
		static::$instance = new static($plugins);

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

	public function __construct($plugins)
	{
		$this->plugins = $plugins;
	}

	public function load($className)
	{
		foreach ($this->plugins as $plugin) {
			$namespace = $plugin->config('namespace');

			$namespacePrefix = $namespace ? $namespace.'\\' : '';

			// プラグインの名前空間下のクラスでないなら
			if (!starts_with($className, $namespacePrefix))
				continue;

			// 名前空間を削る
			$relativeClassName = substr($className, strlen($namespacePrefix));

			// クラスの相対パスを作成する（PSR-4）
			$relativePath = PluginManager::classToPath($relativeClassName);

			// 全ディレクトリ下を探索する (PSR-4)
			foreach ($plugin->config('directories') as $directory) {
				$path = $plugin->path.'/'.$directory.'/'.$relativePath;
				if (file_exists($path)) {
					require_once $path;
					return true;
				}
			}
		}

		return false;
	}

}
