<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ログイン</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">

  <x-header-simple />

  <main class="py-12">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
      <h1 class="text-2xl font-bold text-center mb-10">ログイン</h1>


      <form method="POST" action="{{ route('login') }}" novalidate>

        @csrf

        <div>
          <label class="block text-base font-semibold mb-2">メールアドレス</label>
          <input type="email" name="email" value="{{ old('email') }}"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-base font-semibold mb-2">パスワード</label>
          <input type="password" name="password"
            class="w-full rounded-md border border-gray-300 px-4 py-3">
          @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <button
          class="w-full bg-red-500 text-white py-4 rounded-md font-semibold hover:bg-red-600 text-lg">
          ログインする
        </button>

        <div class="text-center">
          <a href="{{ route('register') }}" class="text-blue-600 font-semibold">
            会員登録はこちら
          </a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
