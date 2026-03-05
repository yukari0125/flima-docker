<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>メール認証</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container">
    <h1 class="page-title">メール認証</h1>
    <p class="lead">
      会員登録ありがとうございます。メールで送信された認証リンクをクリックして認証を完了してください。
    </p>

    @if (session('status') === 'verification-link-sent')
      <p class="status">認証メールを再送しました。</p>
    @endif

    <div class="actions" style="margin-top: 24px;">
      <form novalidate method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="button secondary" type="submit">認証メールを再送する</button>
      </form>

      <form novalidate method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="button" type="submit">ログアウト</button>
      </form>
    </div>
  </main>
</body>
</html>
