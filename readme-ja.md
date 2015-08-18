
# Laravel Extension Pack

[![Build Status](https://travis-ci.org/jumilla/laravel-extension.svg)](https://travis-ci.org/jumilla/laravel-extension)
[![Quality Score](https://img.shields.io/scrutinizer/g/jumilla/laravel-extension.svg?style=flat)](https://scrutinizer-ci.com/g/jumilla/laravel-extension)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/extension/v/stable.svg)](https://packagist.org/packages/laravel-plus/extension)
[![Total Downloads](https://poser.pugx.org/laravel-plus/extension/d/total.svg)](https://packagist.org/packages/extension)
[![Software License](https://poser.pugx.org/laravel-plus/extension/license.svg)](https://packagist.org/packages/laravel-plus/extension)

## 機能

* アドオン機能の追加
	* Laravel 5のディレクトリ構造を複製するイメージで使うことができます。
	* パッケージに独自の名前空間(PSR-4)を一つ持たせることができます。
	* Laravel 5のパッケージとして扱えます。
		* lang, viewの識別子の名前空間表記`{addon-name}::`が使えます。
	* アドオンの追加はディレクトリをコピーするだけ。`config/app.php`などの設定ファイルにコードを追加する必要はありません。
	* 7種類のひな形と2種類のサンプルを用意しています。`php artisan make:addon` で生成できます。

* バージョンベースマイグレーション機能の追加
	* [Laravel Versionia](http://github.com/jumilla/laravel-versionia) を組み込み済みです。

* 名前空間内でのファサード問題の解決
	* appディレクトリ下の名前空間付きクラスの中でファサードが使えます。(バックスラッシュやuse宣言不要)
	* アドオンの名前空間の中からも同じ記述方法でファサードが扱えます。

## インストール方法

### [A] 組み込み済みのLaravelプロジェクトをダウンロードする

```sh
composer create-project laravel-plus/laravel5 <project-name>
```

### [B] 既存のプロジェクトにインストールする

Composerを使います。

```sh
composer require laravel-plus/extension
```

`config/app.php`ファイルを編集します。

```php
	'providers' => [
		↓追加する
		'LaravelPlus\Extension\ServiceProvider',
		...
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		...
	],
```

アドオン設定ファイルをインストールします。
`app/config/addon.php`を生成したい時に、いつでも使えます。

```sh
php artisan addon:status
```

## 動作確認
サンプルとして、アドオン`wiki`を作成します。

```sh
php artisan make:addon wiki sample:ui
```

ルーティング設定を確認してください。

```sh
php artisan route:list
```

ローカルサーバーを立ち上げ、ブラウザで`http://localhost:8000/addons/wiki`にアクセスします。
パッケージ名が表示されれば成功です。

```sh
php artisan serve
```

## コマンド

### `addon:status`

アドオンの状態を確認できます。

```sh
php artisan addon:status
```

`addons`ディレクトリや`app/config/addon.php`ファイルが存在しない場合は作成します。

### `make:addon`

アドオンを作成します。
次のコマンドは、アドオン `blog` を `ui`タイプのひな形を用いて PHP名前空間 `Blog` として生成します。

```sh
php artisan make:addon blog ui
```

ひな形は9種類から選べます。

* `minimum` - 最小構成
* `simple` - `views`ディレクトリと`route.php`があるシンプルな構成
* `library` - PHPクラスとデータベースを提供する構成
* `api` - APIのための構成
* `ui` - UIを含むフルセット
* `debug` - デバッグ機能を収めるアドオン。`debug-bar`のサービスプロバイダ登録も含む。
* `laravel5` - Laravel 5のディレクトリ構成。
* `sample:ui` - UIアドオンのサンプル
* `sample:auth` - Laravel 5に含まれる認証サンプル。

コマンド引数でひな形を指定しない場合、対話形式で選択できます。

```sh
php artisan make:addon blog
```

PHP名前空間は `--namespace` オプションで指定することもできます。
名前空間の区切りは、`\\` か `/` を使ってください。

```sh
php artisan make:addon blog --namespace App\\Blog
php artisan make:addon blog --namespace App/Blog
```

### `addon:name`

アドオン内のファイルを走査し、PHP名前空間を変更します。

```sh
php artisan addon:name blog Sugoi/Blog
```

走査したファイルを確認したい場合は、`-v`オプションを指定してください。

```sh
php artisan addon:name blog Sugoi/Blog -v
```

### `addon:remove`

アドオンを削除します。

```sh
php artisan addon:remove blog;
```

`addons/blog` ディレクトリを削除するだけです。

### `database:status`

マイグレーション、シードの定義とインストール状態を表示します。

```sh
php artisan database:status
```

### `database:upgrade`

すべてのグループのマイグレーションの`up()`を実行し、最新バージョンにします。

```sh
php artisan database:upgrade
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:upgrade --seed <シード>
```

### `database:clean`

すべてのグループのマイグレーションの`down()`を実行し、クリーン状態に戻します。

```sh
php artisan database:clean
```

### `database:refresh`

すべてのグループのマイグレーションをやり直します。

`database:clean`と`database:upgrade`を実行した結果と同じです。

```sh
php artisan database:refresh
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:refresh --seed <シード>
```

### `database:rollback`

指定グループのバージョンをひとつ戻します。

```sh
php artisan database:rollback <グループ>
```

`--all`オプションを付けると、指定グループのすべてのバージョンを削除します。

```sh
php artisan database:rollback <グループ> --all
```

### `database:again`

指定グループの最新バージョンを再作成します。

`database:rollback <グループ>`と`database:upgrade`を実行したときと同じ効果があります。

```sh
php artisan database:again <グループ>
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:again <グループ> --seed <シード>
```

### `database:seed`

指定のシードを実行します。

```sh
php artisan database:seed <シード>
```

`<シード>`を省略した場合、デフォルトのシードを実行します。

```sh
php artisan database:seed
```

## ファサードの拡張
Laravel5のエイリアスローダーはグローバル名前空間にしか作用しないため、名前空間の中からファサードを扱うにはクラス名の先頭に`\`を付けなければなりません。

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

Laravel Extensionは、アドオン下の名前空間内に対してファサードを解決するエイリアスローダーを持っているので、Laravel 4.2 公式ドキュメント記載の方法がそのまま使えます。

```
function index()
{
	return View::make()
}
```

## 起動時の動き

* `addons/{addon-name}/addon.php` の `files`のファイルをrequireします。
* `addons/{addon-name}/addon.php` の `namespace`を見て、`directories`に指定された全てのディレクトリに対しPSR-4規約に基づくクラスオートロードの設定をします。

## 著者

古川 文生 / Fumio Furukawa (fumio@jumilla.me)

## ライセンス

MIT
