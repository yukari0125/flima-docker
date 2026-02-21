<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-gray-900">
        <header class="bg-black text-white">
            <div class="mx-auto flex max-w-6xl items-center gap-6 px-6 py-3">
                <div class="flex items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo-coachtech-header.png') }}" alt="COACHTECH" class="h-6 w-auto" />
                    </a>
                </div>
                <form method="GET" action="{{ route('items.index') }}" class="flex-1">
                    @if (request('tab') === 'mylist')
                        <input type="hidden" name="tab" value="mylist" />
                    @endif
                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="なにをお探しですか？"
                        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder:text-gray-400"
                    />
                </form>
                <nav class="flex items-center gap-6 text-sm">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:opacity-80">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:opacity-80">ログイン</a>
                    @endauth
                    <a href="{{ route('mypage') }}" class="hover:opacity-80">マイページ</a>
                    <a href="{{ route('sell.create') }}" class="rounded-md border border-white bg-white px-3 py-1.5 text-gray-900 hover:bg-gray-100">出品</a>
                </nav>
            </div>
        </header>

        <main class="px-6 py-6">
            {{ $slot }}
        </main>
    </body>
</html>
