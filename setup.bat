@echo off
echo ============================================
echo   Book Manager セットアップスクリプト
echo ============================================
echo.

REM Check PHP
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHPが見つかりません。
    echo PHP 8.2以上をインストールしてください。
    echo https://windows.php.net/download/
    pause
    exit /b 1
)

REM Check Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composerが見つかりません。
    echo https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [1/6] Composerパッケージをインストール中...
composer install --no-interaction

echo.
echo [2/6] SQLiteデータベースファイルを作成中...
if not exist "database\database.sqlite" (
    type nul > database\database.sqlite
    echo     database\database.sqlite を作成しました。
) else (
    echo     database\database.sqlite は既に存在します。
)

echo.
echo [3/6] アプリケーションキーを生成中...
php artisan key:generate --ansi

echo.
echo [4/6] マイグレーションを実行中...
php artisan migrate --force

echo.
echo [5/6] サンプルデータを投入中...
php artisan db:seed --force

echo.
echo [6/6] ストレージリンクを作成中...
php artisan storage:link

echo.
echo ============================================
echo   セットアップ完了！
echo ============================================
echo.
echo サーバーを起動するには以下のコマンドを実行してください:
echo   php artisan serve
echo.
echo ブラウザで以下のURLにアクセスしてください:
echo   http://localhost:8000
echo.
pause
