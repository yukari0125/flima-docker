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
                  <img
                   src="{{ asset('images/logo-coachtech-header.png') }}"
                   alt="COACHTECH"
                   class="h-9 w-auto border border-red-500"
                  >
                </div>
                <div class="flex-1">
                    <input
                        type="text"
                        placeholder="なにをお探しですか？"
                        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder:text-gray-400"
                    />
                </div>
                <nav class="flex items-center gap-6 text-sm">
                    @auth
                        <a href="#" class="hover:opacity-80">ログアウト</a>
                    @else
                        <a href="#" class="hover:opacity-80">ログイン</a>
                    @endauth
                    <a href="#" class="hover:opacity-80">マイページ</a>
                    <a href="#" class="rounded-md border border-white px-3 py-1.5 text-white hover:bg-white/10">出品</a>
                </nav>
            </div>
        </header>

        <main class="px-6 py-12">
            {{ $slot }}
        </main>
    </body>
</html>
