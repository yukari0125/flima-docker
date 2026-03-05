<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;

test('guest can view item index and item detail', function () {
    $seller = User::factory()->create();
    $item = Item::create([
        'user_id' => $seller->id,
        'name' => 'ノートPC',
        'brand' => 'Brand',
        'description' => '説明',
        'price' => 12000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $category1 = Category::create(['name' => '家電']);
    $category2 = Category::create(['name' => 'ゲーム']);
    $item->categories()->sync([$category1->id, $category2->id]);

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('ノートPC');

    $this->get(route('items.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('ノートPC')
        ->assertSee('家電')
        ->assertSee('ゲーム');
});

test('authenticated user does not see own items in recommendation list', function () {
    $user = User::factory()->create();
    Item::create([
        'user_id' => $user->id,
        'name' => '自分の商品',
        'brand' => null,
        'description' => '説明',
        'price' => 1000,
        'condition' => '良好',
        'image_path' => null,
    ]);
    Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '他人の商品',
        'brand' => null,
        'description' => '説明',
        'price' => 2000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($user)
        ->get(route('items.index'))
        ->assertOk()
        ->assertDontSee('自分の商品')
        ->assertSee('他人の商品');
});

test('mylist tab shows only favorited items for authenticated user', function () {
    $user = User::factory()->create();
    $item1 = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'いいね商品',
        'brand' => null,
        'description' => '説明',
        'price' => 3000,
        'condition' => '良好',
        'image_path' => null,
    ]);
    $item2 = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '非いいね商品',
        'brand' => null,
        'description' => '説明',
        'price' => 4000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    Favorite::create([
        'user_id' => $user->id,
        'item_id' => $item1->id,
    ]);

    $this->actingAs($user)
        ->get('/?tab=mylist')
        ->assertOk()
        ->assertSee('いいね商品')
        ->assertDontSee('非いいね商品');
});

test('mylist tab shows no items for guest', function () {
    Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'いいね商品',
        'brand' => null,
        'description' => '説明',
        'price' => 3000,
        'condition' => '良好',
        'image_path' => null,
    ]);
    Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '非いいね商品',
        'brand' => null,
        'description' => '説明',
        'price' => 4000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->get('/?tab=mylist')
        ->assertOk()
        ->assertDontSee('いいね商品')
        ->assertDontSee('非いいね商品');
});

test('sold label is shown for purchased items', function () {
    $buyer = User::factory()->create();
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入済み商品',
        'brand' => null,
        'description' => '説明',
        'price' => 5000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    Purchase::create([
        'user_id' => $buyer->id,
        'item_id' => $item->id,
        'payment_method' => 'カード支払い',
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
        'building' => null,
        'price' => $item->price,
    ]);

    $this->actingAs($buyer)
        ->get(route('items.index'))
        ->assertOk()
        ->assertSee('Sold');
});

test('user can search items by partial name', function () {
    Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '青いタンブラー',
        'brand' => null,
        'description' => '説明',
        'price' => 1000,
        'condition' => '良好',
        'image_path' => null,
    ]);
    Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '赤いマグカップ',
        'brand' => null,
        'description' => '説明',
        'price' => 1200,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->get('/?keyword=タン')
        ->assertOk()
        ->assertSee('青いタンブラー')
        ->assertDontSee('赤いマグカップ');
});

test('search keyword is preserved on mylist tab', function () {
    $user = User::factory()->create();
    $item1 = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '白いタンブラー',
        'brand' => null,
        'description' => '説明',
        'price' => 1800,
        'condition' => '良好',
        'image_path' => null,
    ]);
    $item2 = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '黒いバッグ',
        'brand' => null,
        'description' => '説明',
        'price' => 2000,
        'condition' => '良好',
        'image_path' => null,
    ]);
    Favorite::create(['user_id' => $user->id, 'item_id' => $item1->id]);
    Favorite::create(['user_id' => $user->id, 'item_id' => $item2->id]);

    $this->actingAs($user)
        ->get('/?tab=mylist&keyword=タン')
        ->assertOk()
        ->assertSee('白いタンブラー')
        ->assertDontSee('黒いバッグ')
        ->assertSee('value="タン"', false);
});

test('item detail shows required information', function () {
    $seller = User::factory()->create();
    $commenter = User::factory()->create(['name' => 'コメント太郎']);
    $item = Item::create([
        'user_id' => $seller->id,
        'name' => '詳細表示テスト商品',
        'brand' => 'テストブランド',
        'description' => '詳細説明です',
        'price' => 4321,
        'condition' => '目立った傷や汚れなし',
        'image_path' => null,
    ]);
    $category1 = Category::create(['name' => '家電']);
    $category2 = Category::create(['name' => 'ゲーム']);
    $item->categories()->sync([$category1->id, $category2->id]);
    Favorite::create(['user_id' => User::factory()->create()->id, 'item_id' => $item->id]);
    Comment::create(['user_id' => $commenter->id, 'item_id' => $item->id, 'comment' => 'テストコメント']);

    $this->get(route('items.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('詳細表示テスト商品')
        ->assertSee('テストブランド')
        ->assertSee('¥4,321')
        ->assertSee('詳細説明です')
        ->assertSee('家電')
        ->assertSee('ゲーム')
        ->assertSee('目立った傷や汚れなし')
        ->assertSee('コメント太郎')
        ->assertSee('テストコメント');
});

test('authenticated user can like and unlike an item', function () {
    $user = User::factory()->create();
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'いいね対象商品',
        'brand' => null,
        'description' => '説明',
        'price' => 2000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($user)
        ->post(route('items.favorite.store', ['item_id' => $item->id]))
        ->assertRedirect();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);

    $this->actingAs($user)
        ->get(route('items.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('ハートロゴ_ピンク.png');

    $this->actingAs($user)
        ->delete(route('items.favorite.destroy', ['item_id' => $item->id]))
        ->assertRedirect();

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);

    $this->actingAs($user)
        ->get(route('items.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('ハートロゴ_デフォルト.png');
});

test('authenticated user can post comment', function () {
    $user = User::factory()->create();
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'コメント商品',
        'brand' => null,
        'description' => '説明',
        'price' => 3500,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($user)
        ->post(route('items.comment', ['item_id' => $item->id]), [
            'comment' => 'コメント投稿テスト',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'comment' => 'コメント投稿テスト',
    ]);
});

test('guest user cannot post comment', function () {
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'コメント不可商品',
        'brand' => null,
        'description' => '説明',
        'price' => 4000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->post(route('items.comment', ['item_id' => $item->id]), [
        'comment' => '未ログインコメント',
    ])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('comments', 0);
});

test('comment is required and limited to 255 characters', function () {
    $user = User::factory()->create();
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'コメントバリデーション商品',
        'brand' => null,
        'description' => '説明',
        'price' => 4500,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($user)
        ->from(route('items.show', ['item_id' => $item->id]))
        ->post(route('items.comment', ['item_id' => $item->id]), [
            'comment' => '',
        ])
        ->assertRedirect(route('items.show', ['item_id' => $item->id]))
        ->assertSessionHasErrors(['comment' => '商品コメントを入力してください']);

    $this->actingAs($user)
        ->from(route('items.show', ['item_id' => $item->id]))
        ->post(route('items.comment', ['item_id' => $item->id]), [
            'comment' => str_repeat('あ', 256),
        ])
        ->assertRedirect(route('items.show', ['item_id' => $item->id]))
        ->assertSessionHasErrors(['comment' => '商品コメントは255文字以内で入力してください']);
});
