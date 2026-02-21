<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>会員登録</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">

  <x-header-simple />

  <main class="py-12">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
      <h1 class="text-2xl font-bold text-center mb-10">会員登録</h1>

      <form method="POST" action="{{ route('register') }}" class="space-y-8" novalidate>

        @csrf

        <div>
          <label for="name" class="block text-base font-semibold mb-2">ユーザー名</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="block text-base font-semibold mb-2">メールアドレス</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-base font-semibold mb-2">パスワード</label>
          <input id="password" type="password" name="password" required autocomplete="new-password"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-base font-semibold mb-2">確認用パスワード</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <button
          class="w-full bg-red-500 text-white py-4 rounded-md font-semibold hover:bg-red-600 text-lg">
          登録する
        </button>

        <div class="text-center">
          <a href="{{ route('login') }}" class="text-blue-600 font-semibold">
            ログインはこちら
          </a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
