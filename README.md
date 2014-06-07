
# Laravel Extension Pack

## 機能

* 手軽なパッケージ機能の追加
	* appディレクトリを複製するイメージで使うことができる。
	* パッケージに独自の名前空間(PSR-4)を持たせることができる。
	* Laravel4のパッケージとして扱える。(config, viewの識別子の名前空間表記'package-name::'が使える)
	* パッケージの名前空間の中からファサードが扱える。
	* ディレクトリをコピーするだけでパッケージを追加できる。

## コマンド

### php artisan package:setup
パッケージ機能を有効にします。
* packagesディレクトリを作成する。
* app/config/package.phpファイルを作成する。

### php artisan package:make &gt;package-name&lt; {namespace}
パッケージを作成します。
* packagesディレクトリ下に、**package-name**という名前でディレクトリを作成する。
* 以下のディレクトリ構成を作成する。
	* config
		config.php
		package.php
	* controllers
	* lang
	* migrations
	* models
	* views
	routes.php

## ファサード
Laravel4のエイリアスローダーはグローバル名前空間にしか作用しないため、名前空間の中からファサードを扱うにはクラス名の先頭に`\\`を付けなければなりません。

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
