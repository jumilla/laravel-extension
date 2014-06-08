
# Laravel Extension Pack

## 機能

* プラグイン機能の追加
	* appディレクトリを複製するイメージで使うことができます。
	* パッケージに独自の名前空間(PSR-4)を一つ持たせることができます。
	* Laravel4のパッケージとしても扱えます。
		* config, viewの識別子の名前空間表記`package-name::`が使えます。
	* プラグインの追加はディレクトリをコピーするだけ。`config/app.php`にコードを追加する必要はありません。

* 名前空間内でのファサード問題の解決
	* プラグインの名前空間の中からも同じ記述方法でファサードが扱えます。

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

## 設定

T.B.D.

## コマンド

### php artisan plugin:setup
プラグイン機能を有効にします。
* pluginディレクトリを作成する。
* app/config/plugin.phpファイルを作成する。

### php artisan plugin:make &lt;plugin-name&gt; {namespace}
プラグインを作成します。
* packagesディレクトリ下に、**plugin-name**という名前でディレクトリを作成する。
* 以下のディレクトリ構成を作成する。
	* assets/
	* config/
		* config.php
		* plugin.php
	* controllers/
		* BaseController.php
		* SampleController.php
	* lang/
		* en/
		* ja/
	* migrations/
	* models/
	* views/
		* sample.blade.php
	* routes.php

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

Laravel Extensionは、プラグイン下の名前空間内に対してファサードを解決するエイリアスローダーを持っているので、Laravel公式ドキュメント記載の方法がそのまま使えます。

```
function index()
{
	return View::make()
}
```

## 起動時の動き

* プラグインディレクトリ直下の.phpファイルを全てrequireします。
* {plugin-name}/config/plugin.php の `namespace`を見て、クラスオートロードの設定をします。

## 機能追加予定

* プラグイン
	* ServiceProviderの設定
	* assetsのpublish
	* migration
* ビュー
	* ビュー引数を明示的に宣言する方法とチェック機能の追加

