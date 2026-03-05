<x-market-layout :title="__('商品出品')">
    <div class="ui-form-shell ui-form-shell-wide">
        <h1 class="ui-page-title">商品の出品</h1>

        <form novalidate method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data" class="ui-form">
            @csrf
            <div class="ui-field">
                <label>商品画像</label>
                <div class="ui-upload-box">
                    <input id="item-image" type="file" name="image" accept="image/jpeg,image/png" class="sr-only" />
                    <label for="item-image" class="ui-file-button">
                        画像を選択する
                    </label>
                    <p id="item-image-file-name" class="ui-file-name">選択されていません</p>
                </div>
                @error('image')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-section-title">商品の詳細</div>

            <div class="ui-field">
                <label>カテゴリー</label>
                <div class="ui-category-group">
                    @php
                        $categoryList = !empty($categories)
                            ? $categories
                            : ['ファッション','家電','インテリア','レディース','メンズ','コスメ','本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー','おもちゃ','ベビー・キッズ'];
                        $selectedCategories = old('category', []);
                    @endphp
                    @foreach ($categoryList as $category)
                        <label class="inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="category[]" value="{{ $category }}" class="peer sr-only" @checked(in_array($category, $selectedCategories, true)) />
                            <span class="ui-chip">
                                {{ $category }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('category')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>商品の状態</label>
                <select name="condition" class="ui-input">
                    <option value="">選択してください</option>
                    <option value="良好" @selected(old('condition') === '良好')>良好</option>
                    <option value="目立った傷や汚れなし" @selected(old('condition') === '目立った傷や汚れなし')>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" @selected(old('condition') === 'やや傷や汚れあり')>やや傷や汚れあり</option>
                    <option value="状態が悪い" @selected(old('condition') === '状態が悪い')>状態が悪い</option>
                </select>
                @error('condition')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-section-title">商品名と説明</div>

            <div class="ui-field">
                <label>商品名</label>
                <input type="text" name="name" value="{{ old('name') }}" class="ui-input" />
                @error('name')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>ブランド名</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="ui-input" />
                @error('brand')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>商品の説明</label>
                <textarea name="description" class="ui-input ui-textarea">{{ old('description') }}</textarea>
                @error('description')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>販売価格</label>
                <div class="ui-price-input">
                    <span>¥</span>
                    <input type="text" name="price" value="{{ old('price') }}" />
                </div>
                @error('price')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <button class="ui-button-primary">出品する</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('item-image');
            const fileName = document.getElementById('item-image-file-name');

            if (!input || !fileName) {
                return;
            }

            input.addEventListener('change', function (event) {
                const [file] = event.target.files || [];
                fileName.textContent = file ? file.name : '選択されていません';
            });
        });
    </script>
</x-market-layout>
