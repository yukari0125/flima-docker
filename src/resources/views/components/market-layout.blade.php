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
        <link rel="stylesheet" href="{{ asset('css/market.css') }}?v={{ filemtime(public_path('css/market.css')) }}">
    </head>
    <body class="min-h-screen bg-white text-gray-900">
        <header class="market-header">
            <div class="market-header-inner">
                <div>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo-coachtech-header.png') }}" alt="COACHTECH" class="market-logo" />
                    </a>
                </div>
                <form novalidate method="GET" action="{{ route('items.index') }}" class="market-search-form">
                    @if (request('tab') === 'mylist')
                        <input type="hidden" name="tab" value="mylist" />
                    @endif
                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="なにをお探しですか？"
                        class="market-search-input"
                    />
                </form>
                <nav class="market-nav">
                    @auth
                        <form novalidate method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:opacity-80">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:opacity-80">ログイン</a>
                    @endauth
                    <a href="{{ route('mypage') }}" class="hover:opacity-80">マイページ</a>
                    <a href="{{ route('sell.create') }}" class="market-sell-btn">出品</a>
                </nav>
            </div>
        </header>

        <main class="market-main px-0 py-8">
            {{ $slot }}
        </main>
    </body>
</html>
