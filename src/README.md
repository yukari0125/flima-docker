# フリマアプリ（Laravelアプリ本体）

`/src` は Laravel アプリケーション本体です。  
開発手順や提出情報はリポジトリルートの `README.md` を参照してください。

## 主な構成
- 画面ルート: `routes/web.php`
- コントローラー: `app/Http/Controllers`
- FormRequest: `app/Http/Requests`
- モデル: `app/Models`
- 画面: `resources/views`
- テスト: `tests/Feature`, `tests/Unit`

## 認証
- Laravel Fortify を利用
- 会員登録、ログイン、メール認証を実装

## ER図
- 定義: `../docs/er.mmd`
- 画像: `../docs/er.png`

## テスト実行
```bash
docker compose exec app php artisan test
```

特定のテストファイルのみ実行する場合:

```bash
docker compose exec app php artisan test tests/Feature/ItemFeatureTest.php
```
