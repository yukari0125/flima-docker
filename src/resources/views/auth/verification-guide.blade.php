<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>メール認証誘導</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body class="auth-verify-guide">
  <header class="header">
    <img src="/images/logo-coachtech-header.png" alt="COACHTECH">
  </header>

  <main class="container verify-guide-container">
    <p class="lead verify-guide-lead">
      登録していただいたメールアドレスに認証メールを送付しました。<br>
      メール認証を完了してください。
    </p>

    @if (session('status') === 'verification-link-sent')
      <p class="status">認証メールを再送しました。</p>
    @endif

    <div class="actions verify-guide-actions">
      <a class="button secondary verify-guide-button" href="{{ route('verification.notice') }}">
        認証はこちらから
      </a>

      <form novalidate method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="link verify-guide-link" type="submit">認証メールを再送する</button>
      </form>
    </div>
  </main>
</body>
</html>
