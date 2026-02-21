<x-market-layout :title="__('マイページ')">
    @php
        $active = $page === 'buy' ? 'buy' : 'sell';
    @endphp
    <div class="mx-auto max-w-6xl space-y-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-8">
                @php
                    $avatarUrl = !empty($user->avatar_path) ? asset('storage/'.$user->avatar_path) : null;
                @endphp
                <div class="h-20 w-20 overflow-hidden rounded-full bg-gray-300/80">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                    @endif
                </div>
                <p class="text-lg font-semibold">{{ $user->name }}</p>
            </div>
            <a
                href="{{ route('mypage.profile') }}"
                class="rounded-sm border border-rose-400 px-5 py-2 text-xs font-semibold text-rose-500"
            >
                プロフィールを編集
            </a>
        </div>

        <div class="border-b border-gray-300">
            <div class="flex items-center gap-10 px-2 text-sm font-semibold">
                <a
                    href="{{ url('/mypage?page=sell') }}"
                    class="pb-3 {{ $active === 'sell' ? 'text-rose-500' : 'text-gray-500' }}"
                >
                    出品した商品
                </a>
                <a
                    href="{{ url('/mypage?page=buy') }}"
                    class="pb-3 {{ $active === 'buy' ? 'text-rose-500' : 'text-gray-500' }}"
                >
                    購入した商品
                </a>
            </div>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($items as $item)
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="space-y-3">
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
                            <div class="flex h-full items-center justify-center text-sm font-semibold text-gray-700">
                                商品画像
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-semibold text-gray-700">{{ $item->name }}</p>
                </a>
            @empty
                <div class="text-sm text-gray-500">表示できる商品がありません。</div>
            @endforelse
        </div>
    </div>
</x-market-layout>
