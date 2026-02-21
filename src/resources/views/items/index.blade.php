<x-market-layout :title="__('商品一覧')">
    @php
        $activeTab = $tab === 'mylist' ? 'mylist' : 'recommend';
    @endphp

    <div class="mx-auto max-w-6xl">
        <div class="border-b border-gray-300">
            <div class="flex h-14 items-center gap-10 px-2 text-sm font-semibold">
                <a href="{{ url('/') }}"
                   class="pb-3 {{ $activeTab === 'recommend' ? 'text-rose-500' : 'text-gray-500' }}">
                    おすすめ
                </a>
                <a href="{{ url('/?tab=mylist') }}"
                   class="pb-3 {{ $activeTab === 'mylist' ? 'text-rose-500' : 'text-gray-500' }}">
                    マイリスト
                </a>
            </div>
        </div>

        <div class="mt-8 grid justify-items-start gap-x-10 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($items as $item)
                @php
                    $imageUrl = null;
                    if (!empty($item->image_path)) {
                        $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                            ? $item->image_path
                            : asset('storage/'.$item->image_path);
                    }
                @endphp

                <div class="space-y-3">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block">
                     
                        <div class="relative h-40 w-40 overflow-hidden rounded-sm bg-gray-300/80">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                            @else
                                <div class="flex h-full items-center justify-center text-sm font-semibold text-gray-700">
                                    商品画像
                                </div>
                            @endif

                            @if (($item->purchases_count ?? 0) > 0)
                                <span class="absolute left-2 top-2 rounded bg-gray-900/80 px-2 py-0.5 text-[10px] font-semibold text-white">
                                    Sold
                                </span>
                            @endif
                        </div>

                        <p class="text-xs font-semibold text-gray-700">{{ $item->name }}</p>
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
