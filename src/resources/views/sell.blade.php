<x-market-layout :title="__('商品出品')">
    <div class="mx-auto max-w-4xl">
        <h1 class="text-center text-2xl font-semibold">商品の出品</h1>

        <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data" class="mt-10 space-y-10 text-sm">
            @csrf
            <div class="space-y-3">
                <p class="font-semibold text-gray-700">商品画像</p>
                <div class="rounded-sm border border-dashed border-gray-300 bg-white px-6 py-10 text-center">
                    <label class="inline-flex cursor-pointer items-center rounded-sm border border-rose-400 px-4 py-1 text-xs font-semibold text-rose-500">
                        画像を選択する
                        <input type="file" name="image" class="hidden" />
                    </label>
                </div>
                @error('image')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <h2 class="text-base font-semibold text-gray-700">商品の詳細</h2>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-600">カテゴリー</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @php
                            $categoryList = !empty($categories)
                                ? $categories
                                : ['ファッション','家電','インテリア','レディース','メンズ','コスメ','本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー','おもちゃ','ベビー・キッズ'];
                            $selectedCategories = old('category', []);
                        @endphp
                        @foreach ($categoryList as $category)
                            <label class="inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="category[]" value="{{ $category }}" class="peer sr-only" @checked(in_array($category, $selectedCategories, true)) />
                                <span class="rounded-full border border-rose-400 px-3 py-1 text-[10px] font-semibold text-rose-500 peer-checked:bg-rose-500 peer-checked:text-white">
                                    {{ $category }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('category')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">商品の状態</label>
                    <select name="condition" class="mt-2 w-full rounded-sm border border-gray-300 px-3 py-2 text-xs">
                        <option value="">選択してください</option>
                        <option value="良好" @selected(old('condition') === '良好')>良好</option>
                        <option value="目立った傷や汚れなし" @selected(old('condition') === '目立った傷や汚れなし')>目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり" @selected(old('condition') === 'やや傷や汚れあり')>やや傷や汚れあり</option>
                        <option value="状態が悪い" @selected(old('condition') === '状態が悪い')>状態が悪い</option>
                    </select>
                    @error('condition')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <h2 class="text-base font-semibold text-gray-700">商品名と説明</h2>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">商品名</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-sm border border-gray-300 px-3 py-2 text-xs" />
                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">ブランド名</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" class="mt-2 w-full rounded-sm border border-gray-300 px-3 py-2 text-xs" />
                    @error('brand')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">商品の説明</label>
                    <textarea name="description" class="mt-2 h-28 w-full rounded-sm border border-gray-300 px-3 py-2 text-xs">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">販売価格</label>
                    <div class="mt-2 flex items-center rounded-sm border border-gray-300 px-3 py-2 text-xs">
                        <span class="mr-2 text-gray-500">¥</span>
                        <input type="text" name="price" value="{{ old('price') }}" class="w-full border-none p-0 text-xs focus:outline-none" />
                    </div>
                    @error('price')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 text-center">
                <button class="w-80 rounded-sm bg-rose-500 py-3 text-sm font-semibold text-white hover:bg-rose-600">
                    出品する
                </button>
            </div>
        </form>
    </div>
</x-market-layout>
