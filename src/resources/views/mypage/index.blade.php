<x-market-layout :title="__('マイページ')">
    @php
        $active = $page === 'buy' ? 'buy' : 'sell';
    @endphp
    <div class="view-shell mypage-shell">
        <div class="mypage-head">
            <div class="mypage-user">
                @php
                    $avatarUrl = !empty($user->avatar_path) ? asset('storage/'.$user->avatar_path) : null;
                @endphp
                <div class="mypage-avatar">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                    @endif
                </div>
                <p class="mypage-name">{{ $user->name }}</p>
            </div>
            <a href="{{ route('mypage.profile') }}" class="mypage-edit">
                プロフィールを編集
            </a>
        </div>

        <div class="tabs-row">
                <a href="{{ url('/mypage?page=sell') }}" class="{{ $active === 'sell' ? 'is-active' : '' }}">
                    出品した商品
                </a>
                <a href="{{ url('/mypage?page=buy') }}" class="{{ $active === 'buy' ? 'is-active' : '' }}">
                    購入した商品
                </a>
        </div>

        <div class="card-grid">
            @forelse ($items as $item)
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="card-tile">
                    @php
                        $imageUrl = null;
                        if (!empty($item->image_path)) {
                            $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                                ? $item->image_path
                                : asset('storage/'.$item->image_path);
                        }
                    @endphp
                    <div class="card-image">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-3xl font-semibold text-gray-700">
                                商品画像
                            </div>
                        @endif
                    </div>
                    <p class="card-name">{{ $item->name }}</p>
                </a>
            @empty
                <div class="text-sm text-gray-500">表示できる商品がありません。</div>
            @endforelse
        </div>
    </div>
</x-market-layout>
