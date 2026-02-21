<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('items.index', absolute: false));
});

test('email is required on login', function () {
    $response = $this->from('/login')->post('/login', [
        'email' => '',
        'password' => 'password',
    ]);

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
});

test('password is required on login', function () {
    $response = $this->from('/login')->post('/login', [
        'email' => 'test@example.com',
        'password' => '',
    ]);

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
