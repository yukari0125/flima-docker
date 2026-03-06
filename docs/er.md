# ER図

業務テーブル（`users`, `items`, `categories`, `category_item`, `comments`, `favorites`, `purchases`）のER図です。  
現在はカテゴリを `items.category_id` ではなく、`category_item` で多対多管理しています。
購入データ（`purchases`）は、Stripe有効時はCheckout完了後（`payment_status=paid`確認後）に保存されます。
- `favorites` は (`user_id`, `item_id`) の複合ユニーク制約があります。
- `category_item` は (`item_id`, `category_id`) の複合ユニーク制約があります。

- Mermaid定義: `docs/er.mmd`
- 提出用画像: `docs/er.png`

![ER図](er.png)

```mermaid
%% favorites は (user_id, item_id) の複合ユニーク
%% category_item は (item_id, category_id) の複合ユニーク
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string remember_token
        string postal_code
        string address
        string building
        string avatar_path
        text two_factor_secret
        text two_factor_recovery_codes
        timestamp two_factor_confirmed_at
        timestamp created_at
        timestamp updated_at
    }

    ITEMS {
        bigint id PK
        bigint user_id FK
        string name
        string brand
        string description
        int price
        string condition
        string image_path
        string status
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name UK
        timestamp created_at
        timestamp updated_at
    }

    CATEGORY_ITEM {
        bigint id PK
        bigint item_id FK
        bigint category_id FK
        string item_id_category_id UK
        timestamp created_at
        timestamp updated_at
    }

    COMMENTS {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string comment
        timestamp created_at
        timestamp updated_at
    }

    FAVORITES {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string user_id_item_id UK
        timestamp created_at
        timestamp updated_at
    }

    PURCHASES {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string payment_method
        string postal_code
        string address
        string building
        int price
        string status
        timestamp created_at
        timestamp updated_at
    }

    USERS ||--o{ ITEMS : sells
    USERS ||--o{ COMMENTS : writes
    USERS ||--o{ FAVORITES : likes
    USERS ||--o{ PURCHASES : buys

    ITEMS ||--o{ COMMENTS : has
    ITEMS ||--o{ FAVORITES : has
    ITEMS ||--o{ PURCHASES : purchased_as

    ITEMS ||--o{ CATEGORY_ITEM : categorized_by
    CATEGORIES ||--o{ CATEGORY_ITEM : includes
```
