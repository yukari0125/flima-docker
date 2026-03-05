<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>パスワード確認</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container">
    <h1 class="page-title">パスワード確認</h1>
    <p class="lead">続行するために、パスワードを再入力してください。</p>

    <form novalidate class="form" method="POST" action="{{ route('password.confirm') }}">
      @csrf

      <div class="field">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button class="button" type="submit">確認する</button>
    </form>
  </main>
</body>
</html>
