<?php

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Http;

test('purchase screen shows item and address information', function () {
    $buyer = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => 'Tokyo Chiyoda',
        'building' => 'Building 101',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入テスト商品',
        'brand' => null,
        'description' => '説明',
        'price' => 9999,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($buyer)
        ->get(route('purchase.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('購入テスト商品')
        ->assertSee('123-4567')
        ->assertSee('Tokyo Chiyoda');
});

test('user can update shipping address and buy item', function () {
    config()->set('services.stripe.secret_key', '');

    $buyer = User::factory()->create([
        'postal_code' => '111-1111',
        'address' => 'Old Address',
        'building' => 'Old Building',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入対象商品',
        'brand' => null,
        'description' => '説明',
        'price' => 1500,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($buyer)
        ->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'postal_code' => '222-2222',
            'address' => 'New Address',
            'building' => 'New Building',
        ])
        ->assertRedirect(route('purchase.show', ['item_id' => $item->id]));

    $buyer->refresh();
    expect($buyer->postal_code)->toBe('222-2222');
    expect($buyer->address)->toBe('New Address');

    $this->actingAs($buyer)
        ->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ払い',
            'address' => $buyer->address,
        ])
        ->assertRedirect(route('items.index'));

    $purchase = Purchase::where('user_id', $buyer->id)->where('item_id', $item->id)->first();
    expect($purchase)->not->toBeNull();
    expect($purchase->payment_method)->toBe('コンビニ払い');
});

test('purchased item appears as sold and on mypage buy list', function () {
    $buyer = User::factory()->create([
        'postal_code' => '333-3333',
        'address' => 'Address',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '購入後確認商品',
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
        'postal_code' => $buyer->postal_code,
        'address' => $buyer->address,
        'building' => $buyer->building,
        'price' => $item->price,
        'status' => 'completed',
    ]);

    $this->actingAs($buyer)
        ->get(route('items.index'))
        ->assertOk()
        ->assertSee('Sold');

    $this->actingAs($buyer)
        ->get('/mypage?page=buy')
        ->assertOk()
        ->assertSee('購入後確認商品');
});

test('user cannot purchase own item', function () {
    $seller = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
    ]);
    $item = Item::create([
        'user_id' => $seller->id,
        'name' => '自分の商品',
        'brand' => null,
        'description' => '説明',
        'price' => 5000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($seller)
        ->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード支払い',
            'address' => $seller->address,
        ])
        ->assertSessionHasErrors(['purchase' => '自分が出品した商品は購入できません']);

    $this->assertDatabaseCount('purchases', 0);
});

test('already purchased item cannot be purchased again', function () {
    $seller = User::factory()->create();
    $firstBuyer = User::factory()->create([
        'postal_code' => '111-1111',
        'address' => 'Tokyo',
    ]);
    $secondBuyer = User::factory()->create([
        'postal_code' => '222-2222',
        'address' => 'Osaka',
    ]);
    $item = Item::create([
        'user_id' => $seller->id,
        'name' => '売り切れ商品',
        'brand' => null,
        'description' => '説明',
        'price' => 7000,
        'condition' => '良好',
        'image_path' => null,
    ]);

    Purchase::create([
        'user_id' => $firstBuyer->id,
        'item_id' => $item->id,
        'payment_method' => 'コンビニ払い',
        'postal_code' => $firstBuyer->postal_code,
        'address' => $firstBuyer->address,
        'building' => null,
        'price' => $item->price,
    ]);

    $this->actingAs($secondBuyer)
        ->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード支払い',
            'address' => $secondBuyer->address,
        ])
        ->assertSessionHasErrors(['purchase' => 'この商品は売り切れのため購入できません']);

    expect(Purchase::where('item_id', $item->id)->count())->toBe(1);
});

test('payment method choices are shown on purchase page', function () {
    $buyer = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => '支払い方法確認商品',
        'brand' => null,
        'description' => '説明',
        'price' => 999,
        'condition' => '良好',
        'image_path' => null,
    ]);

    $this->actingAs($buyer)
        ->get(route('purchase.show', ['item_id' => $item->id]))
        ->assertOk()
        ->assertSee('支払い方法')
        ->assertSee('コンビニ払い')
        ->assertSee('カード支払い');
});

test('purchase redirects to stripe checkout when stripe is enabled', function () {
    $buyer = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'Stripe購入商品',
        'brand' => null,
        'description' => '説明',
        'price' => 1200,
        'condition' => '良好',
        'image_path' => null,
    ]);

    config()->set('services.stripe.secret_key', 'sk_test_dummy');
    config()->set('services.stripe.checkout_cancel_url', 'https://example.test/cancel');

    Http::fake([
        'https://api.stripe.com/v1/checkout/sessions' => Http::response([
            'url' => 'https://checkout.stripe.com/c/pay/cs_test_123',
        ], 200),
    ]);

    $this->actingAs($buyer)
        ->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード支払い',
            'address' => $buyer->address,
        ])
        ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123');

    expect(Purchase::where('item_id', $item->id)->exists())->toBeFalse();

    Http::assertSent(function ($request) use ($item) {
        return $request->url() === 'https://api.stripe.com/v1/checkout/sessions'
            && $request['mode'] === 'payment'
            && str_contains($request['success_url'], '/purchase/'.$item->id.'/success')
            && str_contains($request['success_url'], 'session_id={CHECKOUT_SESSION_ID}');
    });
});

test('purchase is created when returning from stripe success_url', function () {
    $buyer = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => 'Tokyo',
        'building' => 'Building A',
    ]);
    $item = Item::create([
        'user_id' => User::factory()->create()->id,
        'name' => 'Stripe成功購入商品',
        'brand' => null,
        'description' => '説明',
        'price' => 3200,
        'condition' => '良好',
        'image_path' => null,
    ]);

    config()->set('services.stripe.secret_key', 'sk_test_dummy');

    Http::fake([
        'https://api.stripe.com/v1/checkout/sessions/cs_test_123' => Http::response([
            'payment_status' => 'paid',
            'metadata' => [
                'item_id' => (string) $item->id,
                'user_id' => (string) $buyer->id,
                'payment_method' => 'カード支払い',
                'postal_code' => '123-4567',
                'address' => 'Tokyo',
                'building' => 'Building A',
            ],
        ], 200),
    ]);

    $this->actingAs($buyer)
        ->get(route('purchase.success', [
            'item_id' => $item->id,
            'session_id' => 'cs_test_123',
        ]))
        ->assertRedirect(route('items.index'));

    $this->assertDatabaseHas('purchases', [
        'user_id' => $buyer->id,
        'item_id' => $item->id,
        'payment_method' => 'カード支払い',
        'address' => 'Tokyo',
        'status' => 'completed',
    ]);
});
