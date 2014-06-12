
# Laravel Extension Pack

## 機能

* プラグイン機能の追加
	* appディレクトリを複製するイメージで使うことができます。
	* パッケージに独自の名前空間(PSR-4)を一つ持たせることができます。
	* Laravel4のパッケージとして扱えます。
		* config, viewの識別子の名前空間表記`{plugin-name}::`が使えます。
	* プラグインの追加はディレクトリをコピーするだけ。`app/config/app.php`にコードを追加する必要はありません。

* 名前空間内でのファサード問題の解決
	* プラグインの名前空間の中からも同じ記述方法でファサードが扱えます。

## インストール方法

`composer.json`ファイルを編集します。
行末のカンマはJSON記法に合わせて設定してください。
``` composer.json
	"require": [
		"laravel/framework": "4.*",
		...
		↓追加する
		"jumilla/laravel-extension": "dev-master"
	],
```

以下のコマンドを実行して、Laravel Extension Packをセットアップしてください。
```
$ composer update

もしくは、

$ php composer.phar update
```

`app/config/app.config`ファイルを編集します。
``` app/config/app.config
	'providers' => [
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		...
		↓追加する
		'Jumilla\LaravelExtension\ServiceProvider',
	],
```

プラグイン設定ファイルをインストールします。
`app/config/plugin.php`を生成したい時に、いつでも使えます。
```
$ php artisan plugin:setup
```

## 動作確認
サンプルとして、プラグイン`wiki`を作成します。
プラグインに割り当てられる名前空間は`Wiki`です。(--namespaceオプションで指定することもできます。)
```
$ php artisan plugin:make wiki
```

ルーティング設定を確認してください。
```
$ php artisan route
```

ローカルサーバーを立ち上げ、ブラウザで`http://localhost:8000/plugins/wiki`にアクセスします。
パッケージ名が表示されれば成功です。
```
$ php artisan serve
```

## コマンド

### php artisan plugin:setup
プラグイン機能を有効にします。
* pluginsディレクトリを作成する。
* app/config/plugin.phpファイルを作成する。

### php artisan plugin:make &lt;plugin-name&gt; {--namespace=...}
プラグインを作成します。
* pluginsディレクトリ下に、**plugin-name**という名前でディレクトリを作成する。
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
		* `Lang::getLocale()`/
	* migrations/
	* models/
	* services/
		* ServiceProvider.php
	* views/
		* sample.blade.php
	* routes.php

## ファサードの拡張
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
* `plugins/{plugin-name}/config/plugin.php` の `namespace`を見て、`directories`に指定された全てのディレクトリに対しPSR-4規約に基づくクラスオートロードの設定をします。

## 機能追加予定

* プラグイン
	* ~~ServiceProviderの設定~~ ***完了***
	* assetsのpublish
	* migration
* ビュー
	* ビュー引数を明示的に宣言する方法とチェック機能の追加

