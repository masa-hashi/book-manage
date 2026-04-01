#!/usr/bin/env bash
set -e

echo "============================================"
echo "  Book Manager セットアップスクリプト"
echo "============================================"
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo "[ERROR] PHPが見つかりません。"
    echo "PHP 8.2以上をインストールしてください。"
    exit 1
fi

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "[ERROR] Composerが見つかりません。"
    echo "https://getcomposer.org/download/"
    exit 1
fi

echo "[1/6] Composerパッケージをインストール中..."
composer install --no-interaction

echo ""
echo "[2/6] SQLiteデータベースファイルを作成中..."
touch database/database.sqlite
echo "    database/database.sqlite を作成しました。"

echo ""
echo "[3/6] アプリケーションキーを生成中..."
php artisan key:generate --ansi

echo ""
echo "[4/6] マイグレーションを実行中..."
php artisan migrate --force

echo ""
echo "[5/6] サンプルデータを投入中..."
php artisan db:seed --force

echo ""
echo "[6/6] ストレージリンクを作成中..."
php artisan storage:link

echo ""
echo "============================================"
echo "  セットアップ完了！"
echo "============================================"
echo ""
echo "サーバーを起動するには以下のコマンドを実行してください:"
echo "  php artisan serve"
echo ""
echo "ブラウザで以下のURLにアクセスしてください:"
echo "  http://localhost:8000"
