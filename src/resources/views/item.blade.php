<x-market-layout :title="__('商品詳細')">
    <div class="item-detail-shell">
        <div class="item-detail-grid">
        @php
            $imageUrl = null;
            if (!empty($item->image_path)) {
                $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                    ? $item->image_path
                    : asset('storage/'.$item->image_path);
            }
        @endphp
        <div class="item-detail-image">
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
            @else
                <div class="item-detail-image-placeholder">商品画像</div>
            @endif
        </div>

        <div class="item-detail-main">
            <div class="item-detail-summary">
                <h1 class="item-detail-title">{{ $item->name }}</h1>
                <p class="item-detail-brand">{{ $item->brand ?? 'ブランド名' }}</p>
                <p class="item-detail-price">¥{{ number_format($item->price) }}（税込）</p>
                <div class="item-detail-reaction-row">
                    <div class="item-detail-reaction">
                        @auth
                            <form novalidate method="POST" action="{{ $isFavorited ? route('items.favorite.destroy', ['item_id' => $item->id]) : route('items.favorite.store', ['item_id' => $item->id]) }}">
                                @csrf
                                @if ($isFavorited)
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="inline-flex items-center">
                                    <img
                                        src="{{ asset($isFavorited ? 'images/ハートロゴ_ピンク.png' : 'images/ハートロゴ_デフォルト.png') }}"
                                        alt="いいね"
                                        class="h-10 w-10 object-contain"
                                    />
                                </button>
                            </form>
                        @else
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="h-10 w-10 object-contain" />
                        @endauth
                        <span class="item-detail-reaction-count">{{ $item->favorites_count }}</span>
                    </div>
                    <div class="item-detail-reaction">
                        <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメント" class="h-10 w-10 object-contain" />
                        <span class="item-detail-reaction-count">{{ $item->comments_count }}</span>
                    </div>
                </div>
                <div class="item-detail-buy-wrap">
                    <a
                        href="{{ route('purchase.show', ['item_id' => $item->id]) }}"
                        class="item-detail-primary-btn"
                    >
                        購入手続きへ
                    </a>
                </div>
            </div>

            <div class="item-detail-section">
                <h2 class="item-detail-section-title">商品説明</h2>
                <p class="item-detail-description">
                    {{ $item->description }}
                </p>
            </div>

            <div class="item-detail-section">
                <h2 class="item-detail-section-title">商品の情報</h2>
                <div class="item-detail-info-list">
                    <div class="item-detail-info-row">
                        <span class="item-detail-info-label">カテゴリー</span>
                        @if ($item->categories->isNotEmpty())
                            <div class="item-detail-chip-list">
                                @foreach ($item->categories as $category)
                                    <span class="item-detail-chip">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="item-detail-chip">未設定</span>
                        @endif
                    </div>
                    <div class="item-detail-info-row">
                        <span class="item-detail-info-label">商品の状態</span>
                        <span>{{ $item->condition }}</span>
                    </div>
                </div>
            </div>

            <div class="item-detail-section">
                <h2 class="item-detail-comments-title">コメント({{ $item->comments_count }})</h2>
                <div class="item-detail-comments-list">
                    @forelse ($item->comments as $comment)
                        <div class="item-detail-comment-item">
                            <div class="item-detail-comment-avatar"></div>
                            <div class="item-detail-comment-body">
                                <div class="item-detail-comment-user">{{ $comment->user?->name ?? 'ユーザー' }}</div>
                                <div class="item-detail-comment-text">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">コメントはまだありません。</div>
                    @endforelse
                </div>
            </div>

            <div class="item-detail-section">
                <h2 class="item-detail-comment-form-title">商品へのコメント</h2>
                <form novalidate method="POST" action="{{ route('items.comment', ['item_id' => $item->id]) }}">
                    @csrf
                    <textarea
                        name="comment"
                        class="item-detail-comment-input"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                    <div class="item-detail-comment-submit-wrap">
                        <button class="item-detail-primary-btn">
                            コメントを送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-market-layout>
