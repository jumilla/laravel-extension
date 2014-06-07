
# Laravel Extension Pack

## 機能

* 手軽なパッケージ機能の追加
	* appディレクトリを複製するイメージで使うことができます。
	* パッケージに独自の名前空間(PSR-4)を持たせることができます。
	* Laravel4のパッケージとしても扱えます。
		* config, viewの識別子の名前空間表記`package-name::`が使えます。
	* パッケージを追加はディレクトリをコピーするだけ。`config/app.php`にコードを追加する必要はありません。

* 名前空間内でのファサード問題の解決
	* パッケージの名前空間の中からも同じ記述方法でファサードが扱えます。

## インストール方法

composer.json
``` composer.json
	"require": [
		"jumilla/laravel-extension": "dev-master",
	],
```

以下のコマンドを実行する。
```
composer update
```

app/config/app.config
``` app/config/app.config
	'providers' => [
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		...
		'Jumilla\LaravelExtension\ServiceProvider',
	],
```

## コマンド

### php artisan package:setup
パッケージ機能を有効にします。
* packagesディレクトリを作成する。
* app/config/package.phpファイルを作成する。

### php artisan package:make &lt;package-name&gt; {namespace}
パッケージを作成します。
* packagesディレクトリ下に、**package-name**という名前でディレクトリを作成する。
* 以下のディレクトリ構成を作成する。
	* config
		* config.php
		* package.php
	* controllers
		* config.php
	* lang
	* migrations
	* models
	* views
	routes.php

## ファサード
Laravel4のエイリアスローダーはグローバル名前空間にしか作用しないため、名前空間の中からファサードを扱うにはクラス名の先頭に`\`を付けなければなりません。

```
function index()
{
	return \View::make()
}
```

または、use宣言を使います。

```
use View;

...

function index()
{
	return View::make()
}
```

Laravel Extensionは、パッケージ下の名前空間内に対してファサードを解決するエイリアスローダーを持っているので、Laravel公式ドキュメント記載の方法がそのまま使えます。

```
function index()
{
	return View::make()
}
```
