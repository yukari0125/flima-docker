<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員登録</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body class="auth-register">
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container">
    <h1 class="page-title">会員登録</h1>

    <form novalidate class="form" method="POST" action="{{ route('register') }}">
      @csrf

      <div class="field">
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label for="password_confirmation">確認用パスワード</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        @error('password_confirmation')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button class="button" type="submit">登録する</button>
    </form>

    <a class="link" href="{{ route('login') }}">ログインはこちら</a>
  </main>
</body>
</html>
