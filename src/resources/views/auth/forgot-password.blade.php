<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>パスワード再設定</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container">
    <h1 class="page-title">パスワード再設定</h1>
    <p class="lead">登録メールアドレスを入力してください。再設定用リンクを送信します。</p>

    @if (session('status'))
      <p class="status">{{ session('status') }}</p>
    @endif

    <form novalidate class="form" method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="field">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button class="button" type="submit">再設定リンクを送信</button>
    </form>

    <a class="link" href="{{ route('login') }}">ログインに戻る</a>
  </main>
</body>
</html>
