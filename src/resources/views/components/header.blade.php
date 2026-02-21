<header class="bg-black text-white">
  <div class="mx-auto flex max-w-6xl items-center gap-6 px-6 py-3">
    <a href="{{ url('/') }}" class="flex items-center">
      <img
        src="{{ asset('images/logo-coachtech-header.png') }}"
        alt="COACHTECH"
        class="h-8 w-auto"
      >
    </a>

    <div class="flex-1">
      <input
        type="text"
        placeholder="なにをお探しですか？"
        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder:text-gray-400"
      />
    </div>

    <nav class="flex items-center gap-6 text-sm">
      @auth
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="hover:opacity-80">ログアウト</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="hover:opacity-80">ログイン</a>
      @endauth

      <a href="#" class="hover:opacity-80">マイページ</a>
      <a href="#" class="rounded-md border border-white px-3 py-1.5 text-white hover:bg-white/10">出品</a>
    </nav>
  </div>
</header>
