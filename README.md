# フリマアプリ（flima）

Laravel 11 + Fortify で構築したフリマアプリです。  
会員登録、メール認証、商品出品、購入、いいね、コメント、マイページを実装しています。

## 技術スタック
- PHP `8.3` / Laravel `11`
- Laravel Fortify（認証・メール認証）
- MySQL `8.0`
- Nginx
- CSS / Vite
- Mailhog（メール確認）
- Docker / Docker Compose

## 実装機能
- 会員登録、ログイン、ログアウト（Fortify）
- メール認証（誘導画面、再送、認証後プロフィール設定画面遷移）
- 商品一覧（おすすめ / マイリスト）
- 商品検索（商品名の部分一致、タブ遷移時のキーワード保持）
- 商品詳細（カテゴリ、状態、いいね数、コメント数、コメント一覧）
- いいね登録 / 解除
- コメント投稿（未ログイン不可、バリデーションあり）
- 商品購入（支払い方法選択、配送先変更、購入制御）
- プロフィール表示 / 編集（画像、ユーザー名、郵便番号、住所）
- 商品出品（複数カテゴリ、画像アップロード）

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
- `users` 1 : N `items`（ユーザーは複数商品を出品）
- `users` 1 : N `comments`（ユーザーは複数コメントを投稿）
- `users` 1 : N `favorites`（ユーザーは複数商品をいいね）
- `users` 1 : N `purchases`（ユーザーは複数商品を購入）
- `items` 1 : N `comments`
- `items` 1 : N `favorites`
- `items` 1 : N `purchases`
- `items` N : N `categories`（`category_item` を介して管理）

### 設計上のポイント
- 商品カテゴリは `items.category_id` ではなく `category_item` で管理
- `items.category_id` は削除済み（`2026_02_21_000200_drop_category_id_from_items_table.php`）
- `purchases` は購入時点の配送先と価格を保持し、取引履歴を不変で参照可能

## ER図
- Mermaid: `docs/er.mmd`
- 説明: `docs/er.md`
- 画像: `docs/er.png`

[ER図](docs/er.png)

## セットアップ
```bash
docker compose up -d --build
docker compose exec app composer install
cp src/.env.example src/.env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
docker compose exec node npm install
docker compose exec node npm run build
```

## アクセス
- アプリ: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`
- Mailhog: `http://localhost:8025`

## 開発コマンド
```bash
docker compose exec node npm run dev
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan test
```

## テスト
- Feature / Unit テストを整備済み
- 最新実行結果: `57 passed`（`docker compose exec app php artisan test`）

## メール認証（開発）
- Mailhog で認証メールを確認: `http://localhost:8025`

## Stripe接続（任意）
`src/.env` に設定:

```env
STRIPE_SECRET_KEY=sk_test_...
STRIPE_CHECKOUT_CANCEL_URL=http://localhost:8080/purchase/1
```

購入フロー（Stripe有効時）:
1. 購入ボタン押下で Checkout Session を作成
2. `checkout.stripe.com` へリダイレクト
3. 決済成功後、`/purchase/{item_id}/success?session_id=...` に戻る
4. 戻り先で Stripe セッションを再取得して `paid` を確認
5. 問題なければ `purchases` に保存

`STRIPE_CHECKOUT_SUCCESS_URL` は現在未使用です（成功URLはアプリ側で固定生成）。
`STRIPE_CHECKOUT_CANCEL_URL` は未設定でも動作し、未設定時は購入画面に戻るデフォルトURLが使われます。

設定変更後:

```bash
docker compose exec app php artisan optimize:clear
```
