<?php namespace Jumilla\LaravelExtension;

use Illuminate\Console\AppNamespaceDetectorTrait;

class Application {

	use AppNamespaceDetectorTrait;

	public static function getNamespace()
	{
		return preg_replace('/\\\\+$/', '', (new Application)->getAppNamespace());
	}

}
