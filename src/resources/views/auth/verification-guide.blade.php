<x-market-layout :title="__('メール認証')">
    <div class="mx-auto max-w-2xl rounded-md border border-gray-200 bg-white p-8 text-center">
        <h1 class="text-2xl font-semibold">メール認証誘導画面</h1>
        <p class="mt-4 text-sm text-gray-700">
            会員登録が完了しました。登録したメールアドレスに認証メールを送信しています。
            メール内の認証リンクをクリックして認証を完了してください。
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="mt-4 text-sm font-semibold text-green-600">
                認証メールを再送しました。
            </p>
        @endif

        <div class="mt-8 space-y-4">
            <a
                href="{{ route('verification.notice') }}"
                class="inline-flex w-72 items-center justify-center rounded-sm bg-rose-500 px-6 py-3 text-sm font-semibold text-white hover:bg-rose-600"
            >
                認証はこちらから
            </a>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex w-72 items-center justify-center rounded-sm border border-rose-400 px-6 py-3 text-sm font-semibold text-rose-500 hover:bg-rose-50"
                >
                    認証メールを再送する
                </button>
            </form>
        </div>
    </div>
</x-market-layout>
