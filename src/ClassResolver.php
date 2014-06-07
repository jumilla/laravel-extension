<?php namespace Jumilla\LaravelExtension;

class ClassResolver {

	private static $packages;

	private static $globalClassAliases;

	public static function register($packages, $aliases)
	{
		static::$packages = $packages;
		static::$globalClassAliases = $aliases;

		// TODO check package configuration

		spl_autoload_register(['Jumilla\LaravelExtension\ClassResolver', 'load'], true, true);
	}

	public static function unregister()
	{
		spl_autoload_unregister(['Jumilla\LaravelExtension\ClassResolver', 'load']);
	}

	public static function load($className)
	{
		foreach (static::$packages as $package) {
			$namespace = $package->config('namespace').'\\';
			$includesGlobalAliases = $package->config('includes_global_aliases', true);
			$packageAliases = $package->config('aliases', []);

			// パッケージの名前空間下のクラスなら
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
				if ($packageAliases) {
					foreach ($packageAliases as $alias => $originalClassName) {
						if ($relativeClassName === $alias) {
							class_alias($originalClassName, $className);
							return true;
						}
					}
				}

				// クラスの相対パスを作成する
				$relativePath = str_replace('\\', '/', $relativeClassName).'.php';

				// 全ディレクトリ下を探索する
				foreach ($package->config('directories') as $directory) {
					$path = $package->path.'/'.$directory.'/'.$relativePath;
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
