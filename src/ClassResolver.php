<?php namespace Jumilla\LaravelExtension;

class ClassResolver {

	private static $plugins;

	private static $globalClassAliases;

	public static function register($plugins, $aliases)
	{
		static::$plugins = $plugins;
		static::$globalClassAliases = $aliases;

		// TODO check plugin configuration

		spl_autoload_register(['Jumilla\LaravelExtension\ClassResolver', 'load'], true, true);
	}

	public static function unregister()
	{
		spl_autoload_unregister(['Jumilla\LaravelExtension\ClassResolver', 'load']);
	}

	public static function load($className)
	{
		foreach (static::$plugins as $plugin) {
			$namespace = $plugin->config('namespace').'\\';
			$includesGlobalAliases = $plugin->config('includes_global_aliases', true);
			$pluginAliases = $plugin->config('aliases', []);

			// プラグインの名前空間下のクラスなら
			if (starts_with($className, $namespace)) {
				// 名前空間を削る
				$relativeClassName = substr($className, strlen($namespace));

				// グローバルなエイリアスかチェックする
				if ($includesGlobalAliases) {
					foreach (static::$globalClassAliases as $alias => $originalClassName) {
						if ($relativeClassName === $alias) {
							class_alias($originalClassName, $className);
							return true;
						}
					}
				}

				// パッケージ固有のエイリアスかチェックする
				if ($pluginAliases) {
					foreach ($pluginAliases as $alias => $originalClassName) {
						if ($relativeClassName === $alias) {
							class_alias($originalClassName, $className);
							return true;
						}
					}
				}

				// クラスの相対パスを作成する
				$relativePath = str_replace('\\', '/', $relativeClassName).'.php';

				// 全ディレクトリ下を探索する (PSR-4)
				foreach ($plugin->config('directories') as $directory) {
					$path = $plugin->path.'/'.$directory.'/'.$relativePath;
					if (file_exists($path)) {
						require_once $path;
						return true;
					}
				}
			}
		}

		return false;
	}

}
