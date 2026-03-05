<x-market-layout :title="__('商品一覧')">
    @php
        $activeTab = $tab === 'mylist' ? 'mylist' : 'recommend';
    @endphp

    <div class="view-shell item-index-shell">
        <div class="tabs-row">
            <a href="{{ url('/') }}" class="{{ $activeTab === 'recommend' ? 'is-active' : '' }}">
                    おすすめ
            </a>
            <a href="{{ url('/?tab=mylist') }}" class="{{ $activeTab === 'mylist' ? 'is-active' : '' }}">
                    マイリスト
            </a>
        </div>

        <div class="card-grid">
            @forelse ($items as $item)
                @php
                    $imageUrl = null;
                    if (!empty($item->image_path)) {
                        $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                            ? $item->image_path
                            : asset('storage/'.$item->image_path);
                    }
                @endphp

                <div class="card-tile">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block">
                     
                        <div class="card-image relative">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                            @else
                                <div class="flex h-full items-center justify-center text-3xl font-semibold text-gray-700">
                                    商品画像
                                </div>
                            @endif

                            @if (($item->purchases_count ?? 0) > 0)
                                <span class="absolute left-2 top-2 rounded bg-gray-900/80 px-2 py-1 text-xs font-semibold text-white">
                                    Sold
                                </span>
                            @endif
                        </div>

                        <p class="card-name">{{ $item->name }}</p>
                    </a>

                </div>
            @empty
                @unless ($isGuestMylist ?? false)
                    <div class="text-sm text-gray-500">表示できる商品がありません。</div>
                @endunless
            @endforelse
        </div>
    </div>
</x-market-layout>
