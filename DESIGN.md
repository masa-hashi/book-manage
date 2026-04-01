# 書籍管理アプリ 設計書

## 1. プロジェクト概要

| 項目 | 内容 |
|---|---|
| アプリ名 | Book Manager（書籍管理アプリ） |
| フレームワーク | Laravel 11.51.0 |
| 言語 | PHP 8.3 |
| データベース | SQLite |
| UI | Blade テンプレート + Tailwind CSS（CDN） |
| フォント | Noto Sans JP |
| 開発環境URL | http://127.0.0.1:8000 |
| プロジェクトパス | `C:\Users\xxx_j\book-manager\` |

---

## 2. 機能一覧

| 機能 | 説明 |
|---|---|
| 書籍一覧 | 登録済み書籍をグリッド表示。統計カード・検索フィルター付き |
| 書籍登録 | 新規書籍の登録フォーム（バリデーション付き） |
| 書籍詳細 | 書籍の全情報を表示 |
| 書籍編集 | 登録情報の更新 |
| 書籍削除 | 書籍の削除（確認ダイアログ付き） |
| 検索・フィルター | タイトル・著者・ジャンル・読書状態・カテゴリで絞り込み |
| 読書ステータス管理 | 未読 / 読中 / 読了 の3段階 |
| 評価（星） | 1〜5の星評価 |
| カテゴリ管理 | 書籍をカテゴリに紐付け |
| ページネーション | 12件/ページ |
| 統計ダッシュボード | 全冊数・読了数・読中数・未読数のカード表示 |

---

## 3. ディレクトリ構成

```
book-manager/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── BookController.php     # 書籍CRUD コントローラー
│   └── Models/
│       ├── Book.php                   # 書籍モデル
│       └── Category.php               # カテゴリモデル
├── database/
│   ├── database.sqlite                # SQLiteデータベース本体
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   └── 2024_01_01_000002_create_books_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── CategorySeeder.php         # カテゴリ初期データ（8件）
│       └── BookSeeder.php             # 書籍サンプルデータ（15件）
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          # 共通レイアウト
│       └── books/
│           ├── index.blade.php        # 一覧ページ
│           ├── show.blade.php         # 詳細ページ
│           ├── create.blade.php       # 登録フォーム
│           ├── edit.blade.php         # 編集フォーム
│           └── _form.blade.php        # フォームパーシャル（共通）
├── routes/
│   └── web.php                        # URLルーティング
├── .env                               # 環境設定
└── DESIGN.md                          # 本設計書
```

---

## 4. データベース設計

### 4.1 ER図

```
categories
    └── 1:N ──→ books
```

### 4.2 categories テーブル

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| id | BIGINT | PK, AUTO INCREMENT | カテゴリID |
| name | VARCHAR | NOT NULL | カテゴリ名（例：小説） |
| slug | VARCHAR | UNIQUE, NOT NULL | 英語識別子（例：novel） |
| description | TEXT | NULL | カテゴリ説明 |
| created_at | TIMESTAMP | | 作成日時 |
| updated_at | TIMESTAMP | | 更新日時 |

**初期データ（8件）:**

| name | slug |
|---|---|
| 小説 | novel |
| ビジネス | business |
| 技術書 | tech |
| 自己啓発 | self-help |
| 歴史 | history |
| サイエンス | science |
| 漫画 | manga |
| エッセイ | essay |

### 4.3 books テーブル

| カラム名 | 型 | 制約 | 説明 |
|---|---|---|---|
| id | BIGINT | PK, AUTO INCREMENT | 書籍ID |
| title | VARCHAR | NOT NULL | タイトル |
| author | VARCHAR | NOT NULL | 著者名 |
| publisher | VARCHAR | NULL | 出版社 |
| published_year | INTEGER | NULL | 出版年 |
| isbn | VARCHAR | UNIQUE, NULL | ISBN番号 |
| genre | VARCHAR | NULL | ジャンル（自由入力） |
| description | TEXT | NULL | あらすじ・メモ |
| status | ENUM | NOT NULL, DEFAULT: 未読 | 読書状態（未読/読中/読了） |
| cover_image_url | VARCHAR | NULL | 表紙画像URL |
| category_id | BIGINT | FK, NULL | カテゴリID（nullOnDelete） |
| rating | INTEGER | NULL | 評価（1〜5） |
| read_at | DATE | NULL | 読了日 |
| created_at | TIMESTAMP | | 登録日時 |
| updated_at | TIMESTAMP | | 更新日時 |

---

## 5. ルーティング

| メソッド | URI | コントローラーメソッド | 説明 |
|---|---|---|---|
| GET | `/` | redirect → `books.index` | トップページ（一覧へリダイレクト） |
| GET | `/books` | `BookController@index` | 書籍一覧 |
| GET | `/books/create` | `BookController@create` | 登録フォーム表示 |
| POST | `/books` | `BookController@store` | 書籍登録 |
| GET | `/books/{book}` | `BookController@show` | 書籍詳細 |
| GET | `/books/{book}/edit` | `BookController@edit` | 編集フォーム表示 |
| PUT/PATCH | `/books/{book}` | `BookController@update` | 書籍更新 |
| DELETE | `/books/{book}` | `BookController@destroy` | 書籍削除 |

---

## 6. モデル設計

### 6.1 Book モデル

```
app/Models/Book.php
```

- **リレーション:** `belongsTo(Category::class)`
- **fillable:** title, author, publisher, published_year, isbn, genre, description, status, cover_image_url, category_id, rating, read_at
- **アクセサ:** `getStatusBadgeColorAttribute()` — ステータスに応じた色（green/blue/gray）を返す

### 6.2 Category モデル

```
app/Models/Category.php
```

- **リレーション:** `hasMany(Book::class)`
- **fillable:** name, slug, description

---

## 7. コントローラー設計

### BookController

| メソッド | 処理内容 |
|---|---|
| `index()` | 検索クエリ（title/author/genre/status/category_id）を受け取りフィルタリング。統計値（全冊/読了/読中/未読）を計算してビューへ渡す。12件ページネーション |
| `create()` | カテゴリ一覧・既存ジャンル一覧をビューへ渡す |
| `store()` | バリデーション後にDBへ登録。ISBNの一意性チェック（編集時は自分除外） |
| `show()` | 指定書籍の詳細データを表示 |
| `edit()` | 編集フォームにカテゴリ・ジャンル一覧を渡す |
| `update()` | バリデーション後にDBを更新 |
| `destroy()` | 書籍を削除しリダイレクト |

**バリデーションルール:**

| フィールド | ルール |
|---|---|
| title | required, max:255 |
| author | required, max:255 |
| publisher | nullable, max:255 |
| published_year | nullable, integer, min:1000, max:来年 |
| isbn | nullable, max:20, unique（編集時は自身除外） |
| genre | nullable, max:100 |
| description | nullable |
| status | required, in:未読,読中,読了 |
| cover_image_url | nullable, url, max:500 |
| category_id | nullable, exists:categories,id |
| rating | nullable, integer, min:1, max:5 |
| read_at | nullable, date |

---

## 8. ビュー設計

### 共通レイアウト（`layouts/app.blade.php`）

- Tailwind CSS（CDN）
- Noto Sans JP（Google Fonts）
- ナビゲーションバー（書籍一覧・新規登録リンク）
- フラッシュメッセージ（success / error）
- フッター

### 書籍一覧（`books/index.blade.php`）

```
[統計カード: 全冊数 / 読了 / 読中 / 未読]

[検索フォーム: タイトル / 著者 / ジャンル / 状態 / カテゴリ]

[書籍グリッド (1〜4列レスポンシブ)]
  - 表紙サムネイル
  - タイトル・著者
  - ジャンル・ステータスバッジ
  - 星評価
  - 詳細・編集・削除ボタン

[ページネーション]
```

### 書籍詳細（`books/show.blade.php`）

```
[左: 表紙画像]  [右: タイトル / 著者 / ステータス / 星評価]
                     出版社 / 出版年 / ISBN / ジャンル / カテゴリ / 読了日
[あらすじ・メモ]
[編集ボタン / 削除ボタン]
[登録日・更新日]
```

### 登録・編集フォーム（`_form.blade.php`）

| フィールド | UI部品 |
|---|---|
| タイトル | テキスト入力（必須） |
| 著者 | テキスト入力（必須） |
| 出版社 | テキスト入力 |
| 出版年 | 数値入力（1000〜来年） |
| ISBN | テキスト入力（等幅フォント） |
| ジャンル | テキスト入力 + datalist補完 |
| カテゴリ | セレクトボックス |
| 読書状態 | ラジオボタン（カード型 未読/読中/読了） |
| 評価 | 星アイコン（クリックで1〜5選択） |
| 読了日 | 日付ピッカー |
| 表紙画像URL | URL入力 |
| あらすじ・メモ | テキストエリア（リサイズ可） |

---

## 9. 環境設定

```env
APP_NAME="Book Manager"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
```

DBファイルパス: `database/database.sqlite`

---

## 10. 開発サーバーの起動

```bash
cd C:\Users\xxx_j\book-manager
php artisan serve
```

→ http://127.0.0.1:8000 でアクセス

### その他の主要コマンド

```bash
# マイグレーション実行
php artisan migrate

# シーダー実行（全件）
php artisan db:seed

# カテゴリのみ再投入
php artisan db:seed --class=CategorySeeder

# DB初期化 + 再マイグレーション + シード
php artisan migrate:fresh --seed
```
