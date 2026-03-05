<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新しいパスワード設定</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container">
    <h1 class="page-title">新しいパスワード設定</h1>

    <form novalidate class="form" method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="field">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="field">
        <label for="password">新しいパスワード</label>
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

      <button class="button" type="submit">パスワードを更新</button>
    </form>
  </main>
</body>
</html>
