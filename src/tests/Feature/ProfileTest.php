<?php

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('mypage profile screen is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('mypage.profile'));

    $response->assertOk();
});

test('mypage shows user information and listed items', function () {
    $user = User::factory()->create([
        'name' => 'プロフィール太郎',
        'avatar_path' => 'avatars/sample.jpg',
    ]);
    $listedItem = Item::create([
        'user_id' => $user->id,
        'name' => '出品した商品A',
        'brand' => null,
        'description' => '説明',
        'price' => 1200,
        'condition' => '良好',
        'image_path' => null,
    ]);
    $purchasedItem = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入した商品B',
        'brand' => null,
        'description' => '説明',
        'price' => 2200,
        'condition' => '良好',
        'image_path' => null,
    ]);
    Purchase::create([
        'user_id' => $user->id,
        'item_id' => $purchasedItem->id,
        'payment_method' => 'カード支払い',
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
        'building' => null,
        'price' => $purchasedItem->price,
    ]);

    $this->actingAs($user)
        ->get('/mypage?page=sell')
        ->assertOk()
        ->assertSee('プロフィール太郎')
        ->assertSee('出品した商品A')
        ->assertDontSee('購入した商品B')
        ->assertSee('storage/avatars/sample.jpg');

    $this->actingAs($user)
        ->get('/mypage?page=buy')
        ->assertOk()
        ->assertSee('購入した商品B')
        ->assertDontSee('出品した商品A');
});

test('profile edit page has existing values as defaults', function () {
    $user = User::factory()->create([
        'name' => '初期値ユーザー',
        'postal_code' => '111-2222',
        'address' => 'Tokyo Minato',
        'building' => 'Initial Building',
        'avatar_path' => 'avatars/default.jpg',
    ]);

    $this->actingAs($user)
        ->get(route('mypage.profile'))
        ->assertOk()
        ->assertSee('value="初期値ユーザー"', false)
        ->assertSee('value="111-2222"', false)
        ->assertSee('value="Tokyo Minato"', false)
        ->assertSee('value="Initial Building"', false)
        ->assertSee('storage/avatars/default.jpg');
});

test('profile information can be updated on mypage profile', function () {
    $user = User::factory()->create([
        'name' => 'Before Name',
        'postal_code' => '111-1111',
        'address' => 'Before Address',
        'building' => 'Before Building',
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('mypage.profile.update'), [
            'name' => 'After Name',
            'postal_code' => '123-4567',
            'address' => 'After Address',
            'building' => 'After Building',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('mypage.profile'));

    $user->refresh();
    expect($user->name)->toBe('After Name');
    expect($user->postal_code)->toBe('123-4567');
    expect($user->address)->toBe('After Address');
    expect($user->building)->toBe('After Building');
});

test('avatar image is stored when profile is updated', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'postal_code' => '111-1111',
        'address' => 'Tokyo',
    ]);

    $avatar = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

    $response = $this
        ->actingAs($user)
        ->post(route('mypage.profile.update'), [
            'name' => $user->name,
            'postal_code' => '123-4567',
            'address' => 'Osaka',
            'building' => 'Apt',
            'avatar' => $avatar,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('mypage.profile'));

    $user->refresh();
    expect($user->avatar_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar_path);
});
