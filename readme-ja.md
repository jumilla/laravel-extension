
# Laravel Extension Pack

[![Build Status](https://travis-ci.org/jumilla/laravel-extension.svg)](https://travis-ci.org/jumilla/laravel-extension)
[![Quality Score](https://img.shields.io/scrutinizer/g/jumilla/laravel-extension.svg?style=flat)](https://scrutinizer-ci.com/g/jumilla/laravel-extension)
[![Code Coverage](https://scrutinizer-ci.com/g/jumilla/laravel-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jumilla/laravel-extension/)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/extension/v/stable.svg)](https://packagist.org/packages/laravel-plus/extension)
[![Total Downloads](https://poser.pugx.org/laravel-plus/extension/d/total.svg)](https://packagist.org/packages/laravel-plus/extension)
[![Software License](https://poser.pugx.org/laravel-plus/extension/license.svg)](https://packagist.org/packages/laravel-plus/extension)

## 機能

* バージョンベースマイグレーション機能の追加
	* セマンティックバージョンベースのデータベースマイグレーションライブラリ [Laravel Versionia](http://github.com/jumilla/laravel-versionia) を採用しました。
	* マイグレーション／シードクラスは、Laravel 5のディレクトリ構造に組み込まれました。**app\Database\Migrations** と **app\Database\Seeds** ディレクトリを使ってください。
	* マイグレーションにグループを指定できるようになりました。
	* シードに名前が付けられるようになりました。
	* バージョンとの指定は、`App/Providers/DatabaseServiceProvider` クラスで行います。
	* Laravelのマイグレーション／シードクラスをそのまま利用できます。

* アドオン機能の追加
	* アプリケーション内のパッケージ機能です。Laravel 5のディレクトリ構造を複製するイメージで使うことができます。
	* デフォルトで、**addons** ディレクトリの下に配置されます。
	* アドオンに独自の名前空間(PSR-4)を一つ持たせることができます。
	* Laravel 5のパッケージとして扱えます。lang, viewの識別子の名前空間表記`{addon-name}::`が使えます。configも使えます。
	* アドオンの追加はディレクトリをコピーするだけ。**config/app.php** などの設定ファイルにコードを追加する必要はありません。
	* 7種類のひな形と2種類のサンプルを用意しています。`php artisan make:addon` で生成できます。

* ファイル生成コマンド
	* コンソールコマンドやジョブ、プロバイダーなどLaravel 5のクラスをコマンドラインから生成できる機能です。
	* ジェネレーターコマンドに、カスタマイズしたスタブファイルを使用させることもできます。
	* Laravel 5の`make:xxx`コマンドに準拠しています。コマンド名やオプションは同じです。
	* `--addon`オプションで、アドオン内にファイルを生成することもできます。

* 名前空間内でのファサード問題の解決
	* appディレクトリ下の名前空間付きクラスの中でファサードが使えます。(バックスラッシュ指定やuse宣言不要)
	* アドオンの名前空間付きクラスの中からも、同じ記述方法でファサードが扱えます。

## インストール方法

### [A] 組み込み済みのLaravelプロジェクトをダウンロードする

```sh
composer create-project laravel-plus/laravel5 <project-name>
```

### [B] 既存のプロジェクトにインストールする

#### 1. Composerで`laravel-plus/extension`パッケージを追加します。

```sh
composer require laravel-plus/extension
```

#### 2. サービスプロバイダーを追加します。

**config/app.php** ファイルの`providers`セクションに、`LaravelPlus\Extension\ServiceProvider`クラスを追加してください。

```php
	'providers' => [
		Illuminate\Foundation\Providers\ArtisanServiceProvider:class,
		...
		↓次の行を追加する
		LaravelPlus\Extension\ServiceProvider::class,
	],
```

#### 3. `App\Console\Kernel`クラスのベースクラスを設定します。

**app/Console/Kernel.php** ファイルを開き、`use Illuminate\Foundation\Console\Kernel as ConsoleKernel;`の行を次のように変更してください。

```php
use LaravelPlus\Extension\ConsoleKernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
}
```

#### 4. `App\Http\Kernel`クラスのベースクラスを設定します。

**app/Http/Kernel.php** ファイルを開き、`use Illuminate\Foundation\Http\Kernel as HttpKernel;`の行を次のように変更してください。

```php
use LaravelPlus\Extension\HttpKernel as HttpKernel;

class Kernel extends HttpKernel
{
}
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

### database:status

マイグレーション、シードの定義とインストール状態を表示します。

```sh
php artisan database:status
```

### database:upgrade

すべてのグループのマイグレーションの`up()`を実行し、最新バージョンにします。

```sh
php artisan database:upgrade
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:upgrade --seed <シード>
```

### database:clean

すべてのグループのマイグレーションの`down()`を実行し、クリーン状態に戻します。

```sh
php artisan database:clean
```

### database:refresh

すべてのグループのマイグレーションをやり直します。

`database:clean`と`database:upgrade`を実行した結果と同じです。

```sh
php artisan database:refresh
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:refresh --seed <シード>
```

### database:rollback

指定グループのバージョンをひとつ戻します。

```sh
php artisan database:rollback <グループ>
```

`--all`オプションを付けると、指定グループのすべてのバージョンを削除します。

```sh
php artisan database:rollback <グループ> --all
```

### database:again

指定グループの最新バージョンを再作成します。

`database:rollback <グループ>`と`database:upgrade`を実行したときと同じ効果があります。

```sh
php artisan database:again <グループ>
```

マイグレーション後にシードを実行させることもできます。

```sh
php artisan database:again <グループ> --seed <シード>
```

### database:seed

指定のシードを実行します。

```sh
php artisan database:seed <シード>
```

`<シード>`を省略した場合、デフォルトのシードを実行します。

```sh
php artisan database:seed
```

### addon:status

アドオンの状態を確認できます。

```sh
php artisan addon:status
```

`addons`ディレクトリや`app/config/addon.php`ファイルが存在しない場合は作成します。

### addon:name

アドオン内のファイルを走査し、PHP名前空間を変更します。

```sh
php artisan addon:name blog Sugoi/Blog
```

走査したファイルを確認したい場合は、`-v`オプションを指定してください。

```sh
php artisan addon:name blog Sugoi/Blog -v
```

### addon:remove

アドオンを削除します。

```sh
php artisan addon:remove blog;
```

`addons/blog` ディレクトリを削除するだけです。

### make:addon

アドオンを作成します。
次のコマンドは、アドオン `blog` を `ui`タイプのひな形を用いて PHP名前空間 `Blog` として生成します。

```sh
php artisan make:addon blog ui
```

ひな形は9種類から選べます。

- **minimum** - 最小構成
- **simple** - **views** ディレクトリと **Http/route.php** があるシンプルな構成
- **library** - PHPクラスとデータベースを提供する構成
- **api** - APIのための構成
- **ui** - UIを含むフルセット
- **debug** - デバッグ機能を収めるアドオン。'debug-bar'のサービスプロバイダ登録も含む。
- **generator** - カスタマイズ用スタブファイル。
- **laravel5** - Laravel 5のディレクトリ構成。
- **sample:ui** - UIアドオンのサンプル
- **sample:auth** - Laravel 5に含まれる認証サンプル。

コマンド引数でひな形を指定しない場合、対話形式で選択できます。

```sh
php artisan make:addon blog
```

PHP名前空間は `--namespace` オプションで指定することもできます。
名前空間の区切りには、`\\` か `/` を使ってください。

```sh
php artisan make:addon blog --namespace App\\Blog
php artisan make:addon blog --namespace App/Blog
```

### make:console

artisanコマンドクラスを生成します。

名前に`foo`を指定すると、`app/Console/Commands/Foo.php` ファイルを生成します。

```sh
$ php artisan make:console foo
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Console/Commands/Foo.php` ファイルを生成します。

```sh
$ php artisan make:console foo --addon=blog
```

### make:controller

コントローラークラスを生成します。

名前に`FooController`を指定すると、`app/Http/Controllers/FooController.php` ファイルを生成します。

```sh
$ php artisan make:controller FooController
```

`--resource` オプションを指定すると、リソースコントローラーを生成します。

```sh
$ php artisan make:controller FooController --resource
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Http/Controllers/FooController.php` ファイルを生成します。

```sh
$ php artisan make:controller FooController --addon=blog
```

### make:event

イベントクラスを生成します。

名前に`FooEvent`を指定すると、`app/Events/FooEvent.php` ファイルを生成します。

```sh
$ php artisan make:event FooEvent
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Events/FooEvent.php` ファイルを生成します。

```sh
$ php artisan make:event FooEvent --addon=blog
```

### make:job

ジョブクラスを生成します。

名前に`FooJob`を指定すると、`app/Jobs/FooJob.php` ファイルを生成します。

```sh
$ php artisan make:job FooJob
```

`--queued` オプションを指定すると、`ShouldQueue` インターフェースを実装したジョブクラスを生成します。

```sh
$ php artisan make:job FooJob --queued
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Jobs/FooJob.php` ファイルを生成します。

```sh
$ php artisan make:job FooJob --addon=blog
```

もし Laravel 5.0 の `App/Commands` ディレクトリを使っているなら、`app/Commands/FooCommand.php` も生成できます。

```sh
$ php artisan make:job /Commands/FooCommand
```

### make:listener

リスナークラスを生成します。

名前に`FooListener`を指定すると、`app/Listeners/FooListener.php` ファイルを生成します。
`--event` オプションは必須です。

```sh
$ php artisan make:listener FooListener --event=bar
```

`--queued` オプションを指定すると、`ShouldQueue` インターフェースを実装したリスナークラスを生成します。

```sh
$ php artisan make:listener FooListener --event=bar --queued
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Listeners/FooListener.php` ファイルを生成します。

```sh
$ php artisan make:listener FooListener --event=bar --addon=blog
```

### make:middleware

ミドルウェアクラスを生成します。

名前に`foo`を指定すると、`app/Http/Middleware/Foo.php` ファイルを生成します。

```sh
$ php artisan make:middleware foo
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Http/Middleware/Foo.php` ファイルを生成します。

```sh
$ php artisan make:middleware foo --addon=blog
```

### make:migration

マイグレーションクラスを生成します。

名前に`foo`を指定すると、`app/Database/Migrations/App_1_0.php` ファイルを生成します。

```sh
$ php artisan make:migration App_1_0
```

`--create` オプションに `materials` を指定すると、**materials** テーブルを作成するマイグレーションクラスを生成します。

```sh
$ php artisan make:migration App_1_1 --create=materials
```

`--update` オプションに `materials` を指定すると、**materials** テーブルを更新するマイグレーションクラスを生成します。

```sh
$ php artisan make:migration App_1_2 --update=materials
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Database/Migrations/Blog_1_0.php` ファイルを生成します。

```sh
$ php artisan make:migration Blog_1_0 --addon=blog
```

### make:model

Eloquentモデルクラスを生成します。

名前に`foo`を指定すると、**foos** テーブルに対応した `app/Foo.php` ファイルを生成します。

```sh
$ php artisan make:model foo
```

名前に`services/models/foo`を指定すると、**foos** テーブルに対応した `app/Services/Models/Foo.php` ファイルを生成します。
PHP名前空間は `App\Services\Models` になります。

```sh
$ php artisan make:model services/models/foo
```

`--migration` オプションに `App_1_1` を指定すると、マイグレーションファイルも一緒に生成します。
`php artisan make:migration App_1_1 --create=foos` コマンドを実行した結果と同じです。

```sh
$ php artisan make:model foo --migration=App_1_1
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Foo.php` ファイルを生成します。

```sh
$ php artisan make:model foo --addon=blog
```

### make:policy

ポリシークラスを生成します。

名前に`foo`を指定すると、`app/Policies/Foo.php` ファイルを生成します。

```sh
$ php artisan make:policy foo
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Policies/Foo.php` ファイルを生成します。

```sh
$ php artisan make:policy foo --addon=blog
```

### make:provider

サービスプロバイダークラスを生成します。

名前に`FooServiceProvider`を指定すると、`app/Providers/FooServiceProvider.php` ファイルを生成します。

```sh
$ php artisan make:provider FooServiceProvider
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Providers/FooServiceProvider.php` ファイルを生成します。

```sh
$ php artisan make:provider FooServiceProvider --addon=blog
```

### make:request

フォームリクエストクラスを生成します。

名前に`FooRequest`を指定すると、`app/Http/Requests/FooRequest.php` ファイルを生成します。

```sh
$ php artisan make:request FooRequest
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Http/Requests/FooRequest.php` ファイルを生成します。

```sh
$ php artisan make:request FooRequest --addon=blog
```

### make:seeder

シードクラスを生成します。

名前に`staging`を指定すると、`app/Database/Seeds/Staging.php` ファイルを生成します。

```sh
$ php artisan make:request staging
```

`--addon` オプションに `blog` を指定すると、`addons/blog/classes/Database/Seeds/Staging.php` ファイルを生成します。

```sh
$ php artisan make:request staging --addon=blog
```

### make:test

PHPUnitテストスイートを生成します。

名前に`FooTests`を指定すると、`tests/FooTests.php` ファイルを生成します。

```sh
$ php artisan make:test FooTests
```

`--addon` オプションに `blog` を指定すると、`addons/blog/tests/FooTests.php` ファイルを生成します。

```sh
$ php artisan make:test FooTests --addon=blog
```

## ファサードの拡張

Laravel5のエイリアスローダーはグローバル名前空間にしか作用しないため、名前空間の中からファサードを扱うにはクラス名の先頭に`\`を付けなければなりません。

```
function index()
{
	return \View::make();
}
```

または、use宣言を使います。

```
use View;

...

function index()
{
	return View::make();
}
```

Laravel Extensionはファサードを解決するエイリアスローダーを持っているので、`app`と`addons`ディレクトリ下の名前空間付きのPHPクラスに対してこれらの記述が不要です。
`vendor`ディレクトリ下のパッケージには作用しないので安心です。

```
function index()
{
	return View::make();	// スッキリ！
}
```

## 起動時の動き

* `addons/{addon-name}/addon.php` の `files`のファイルをrequireします。
* `addons/{addon-name}/addon.php` の `namespace`を見て、`directories`に指定された全てのディレクトリに対しPSR-4規約に基づくクラスオートロードの設定をします。

## 著者

古川 文生 / Fumio Furukawa (fumio@jumilla.me)

## ライセンス

MIT
