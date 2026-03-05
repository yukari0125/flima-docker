<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('name is required on register', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['name' => 'お名前を入力してください']);
});

test('email is required on register', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => '',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
});

test('password is required on register', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
});

test('password must be at least 8 characters on register', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '1234567',
        'password_confirmation' => '1234567',
    ]);

    $response
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
});

test('password confirmation must match on register', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different-password',
    ]);

    $response
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'registered-user@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    expect(User::where('email', 'registered-user@example.com')->exists())->toBeTrue();
    $response->assertRedirect(route('verification.guide', absolute: false));
});
