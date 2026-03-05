<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body class="auth-login">
  <header class="header">
    <a href="{{ route('items.index') }}">
      <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
    </a>
  </header>

  <main class="container">
    <h1 class="page-title">ログイン</h1>

    <form novalidate class="form" method="POST" action="{{ route('login') }}">
      @csrf

      <div class="field">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button class="button" type="submit">ログインする</button>
    </form>

    <a class="link" href="{{ route('register') }}">会員登録はこちら</a>
  </main>
</body>
</html>
