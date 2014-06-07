
# Laravel Extension Pack

## 機能

- 手軽なパッケージ機能の追加
-- appディレクトリを複製するイメージで使うことができる。
-- パッケージに独自の名前空間を持たせることができる。
-- Laravel4のパッケージとして扱える。(config, viewの識別子の名前空間として)
-- パッケージの名前空間の中からファサードが扱える。
-- ディレクトリをコピーするだけでパッケージを追加できる。

## コマンド

### php artisan package:setup
パッケージ機能を有効にします。
- packagesディレクトリを作成する。
- app/config/package.phpファイルを作成する。

### php artisan package:make <package-name> (namespace)
パッケージを作成します。
