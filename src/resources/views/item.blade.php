<x-market-layout :title="__('商品詳細')">
    <div class="mx-auto grid max-w-5xl gap-12 lg:grid-cols-[360px_1fr]">
        @php
            $imageUrl = null;
            if (!empty($item->image_path)) {
                $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                    ? $item->image_path
                    : asset('storage/'.$item->image_path);
            }
        @endphp
        <div class="aspect-square w-full overflow-hidden rounded-sm bg-gray-300/80">
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
            @else
                <div class="flex h-full items-center justify-center text-sm font-semibold text-gray-700">商品画像</div>
            @endif
        </div>

        <div class="space-y-8 text-sm">
            <div>
                <h1 class="text-2xl font-semibold">{{ $item->name }}</h1>
                <p class="mt-1 text-xs text-gray-500">{{ $item->brand ?? 'ブランド名' }}</p>
                <p class="mt-4 text-xl font-semibold">¥{{ number_format($item->price) }}（税込）</p>
                <div class="mt-3 flex items-center gap-6 text-xs text-gray-700">
                    <div class="flex items-center gap-2">
                        @auth
                            <form method="POST" action="{{ $isFavorited ? route('items.favorite.destroy', ['item_id' => $item->id]) : route('items.favorite.store', ['item_id' => $item->id]) }}">
                                @csrf
                                @if ($isFavorited)
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="inline-flex items-center">
                                    <img
                                        src="{{ asset($isFavorited ? 'images/ハートロゴ_ピンク.png' : 'images/ハートロゴ_デフォルト.png') }}"
                                        alt="いいね"
                                        class="h-4 w-4"
                                    />
                                </button>
                            </form>
                        @else
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="h-4 w-4" />
                        @endauth
                        <span>{{ $item->favorites_count }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント" class="h-4 w-4" />
                        <span>{{ $item->comments_count }}</span>
                    </div>
                </div>
                <div class="mt-5">
                    <a
                        href="{{ route('purchase.show', ['item_id' => $item->id]) }}"
                        class="inline-flex w-full items-center justify-center rounded-sm bg-rose-500 py-2 text-sm font-semibold text-white hover:bg-rose-600"
                    >
                        購入手続きへ
                    </a>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-semibold">商品説明</h2>
                <p class="mt-2 text-xs leading-6 text-gray-700 whitespace-pre-line">
                    {{ $item->description }}
                </p>
            </div>

            <div>
                <h2 class="text-sm font-semibold">商品の情報</h2>
                <div class="mt-3 space-y-2 text-xs text-gray-700">
                    <div class="flex items-center gap-4">
                        <span class="w-20 text-gray-500">カテゴリー</span>
                        @if ($item->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($item->categories as $category)
                                    <span class="rounded-full bg-gray-200 px-3 py-0.5">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="rounded-full bg-gray-200 px-3 py-0.5">未設定</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-20 text-gray-500">商品の状態</span>
                        <span>{{ $item->condition }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-semibold">コメント ({{ $item->comments_count }})</h2>
                <div class="mt-3 space-y-4 text-xs">
                    @forelse ($item->comments as $comment)
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-full bg-gray-300"></div>
                            <div class="flex-1">
                                <div class="font-semibold">{{ $comment->user?->name ?? 'ユーザー' }}</div>
                                <div class="mt-2 rounded-sm bg-gray-200 px-3 py-2 text-gray-700">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">コメントはまだありません。</div>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-sm font-semibold">商品へのコメント</h2>
                <form method="POST" action="{{ route('items.comment', ['item_id' => $item->id]) }}">
                    @csrf
                    <textarea
                        name="comment"
                        class="mt-3 h-28 w-full rounded-sm border border-gray-400 px-3 py-2 text-sm focus:border-gray-700 focus:outline-none"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-4">
                        <button class="w-full rounded-sm bg-rose-500 py-2 text-sm font-semibold text-white hover:bg-rose-600">
                            コメントを送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-market-layout>
