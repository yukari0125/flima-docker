<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('sell page requires authentication', function () {
    $this->get(route('sell.create'))
        ->assertRedirect(route('login'));
});

test('authenticated user can exhibit item with multiple categories and image upload', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    Category::create(['name' => '家電']);
    Category::create(['name' => 'ゲーム']);

    $response = $this->actingAs($user)
        ->post(route('sell.store'), [
            'image' => UploadedFile::fake()->create('item.jpg', 120, 'image/jpeg'),
            'category' => ['家電', 'ゲーム'],
            'condition' => '良好',
            'name' => '出品テスト商品',
            'brand' => 'Brand',
            'description' => 'テスト説明',
            'price' => 1234,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status', 'exhibition-submitted');

    $item = Item::where('name', '出品テスト商品')->first();
    expect($item)->not->toBeNull();
    expect($item->categories()->count())->toBe(2);
    Storage::disk('public')->assertExists($item->image_path);
});

test('sell validation messages are shown when required fields are missing', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from(route('sell.create'))
        ->post(route('sell.store'), [
            'name' => '',
            'description' => '',
            'price' => '',
            'condition' => '',
            'category' => [],
        ]);

    $response
        ->assertRedirect(route('sell.create'))
        ->assertSessionHasErrors([
            'image' => '商品画像を選択してください',
            'category' => '商品のカテゴリーを選択してください',
            'condition' => '商品の状態を選択してください',
            'name' => '商品名を入力してください',
            'description' => '商品説明を入力してください',
            'price' => '商品価格を入力してください',
        ]);
});
