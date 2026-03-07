# フリマアプリ（flima）

Laravel 11 + Fortify を使用して作成したフリマアプリです。  
会員登録・メール認証・商品出品・購入・いいね・コメント・マイページ機能を実装しています。

## リポジトリ

```bash
git clone https://github.com/yukari0125/flima-docker.git
cd flima-docker
```

## 技術スタック

- PHP 8.3
- Laravel 11
- Laravel Fortify（認証 / メール認証）
- MySQL 8.0
- Nginx
- Vite
- Mailhog（メール確認）
- Docker / Docker Compose

## 実装機能

### 認証

- 会員登録
- ログイン / ログアウト
- メール認証（Fortify）

### 商品機能

- 商品一覧
- 商品検索（商品名部分一致）
- 商品詳細表示
- 商品出品
- 商品購入

### コミュニケーション

- いいね登録 / 解除
- コメント投稿

### マイページ

- プロフィール表示
- プロフィール編集
- 出品商品一覧
- 購入商品一覧


## データ設計

### 主要テーブル

- `users`: ユーザー情報（認証・プロフィール）
- `items`: 出品商品（出品者、価格、状態、画像、販売ステータス）
- `categories`: カテゴリマスタ
- `category_item`: 商品とカテゴリの中間テーブル（多対多）
- `comments`: 商品コメント
- `favorites`: いいね
- `purchases`: 購入履歴（配送先・支払い方法・購入時価格）


### リレーション

- `users` 1:N `items`
- `users` 1:N `comments`
- `users` 1:N `favorites`
- `users` 1:N `purchases`
- `items` 1:N `comments`
- `items` 1:N `favorites`
- `items` 1:N `purchases`
- `items` N:N `categories`（`category_item`）

### 設計ポイント

- 商品カテゴリは中間テーブル（`category_item`）で管理
- `items.category_id` は削除済み
- `purchases` は購入時点の配送先・価格を保存

## ER図

- `docs/er.png`

## 環境構築

1. Docker 起動

```bash
docker compose up -d --build
```

2. Laravel 依存インストール

```bash
docker compose exec app composer install
```

3. 環境変数作成

```bash
cp src/.env.example src/.env
```

4. APP キー生成

```bash
docker compose exec app php artisan key:generate
```

5. DB マイグレーション + Seeder

```bash
docker compose exec app php artisan migrate --seed
```

6. storage リンク作成

```bash
docker compose exec app php artisan storage:link
```

7. 権限付与

```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
```

8. フロントエンドビルド

```bash
docker compose run --rm node npm install
docker compose run --rm node npm run build
```

## アクセス

- アプリ: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`
- Mailhog: `http://localhost:8025`

## 開発コマンド

### キャッシュクリア

```bash
docker compose exec app php artisan optimize:clear
```

### テスト実行

```bash
docker compose exec app php artisan test
```

## メール確認（開発）

Mailhog を使用しています。  
http://localhost:8025 で認証メールを確認できます。

## Stripe接続（任意）

`.env` に設定:

```env
STRIPE_SECRET_KEY=sk_test_...
STRIPE_CHECKOUT_CANCEL_URL=http://localhost:8080/purchase/1
```

設定変更後:

```bash
docker compose exec app php artisan optimize:clear
```

## 注意事項

- 本アプリは PHP 8.3 環境で動作します。
- 依存関係更新は `app` コンテナ内で実行してください。

```bash
docker compose exec app composer update
```

## 補足

Mailhog を使用しているため、開発環境では実際のメール送信は行われません。
