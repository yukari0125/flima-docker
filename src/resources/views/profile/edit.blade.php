<x-market-layout :title="__('プロフィール設定')">
    <div class="ui-form-shell ui-auth-font">
        <h1 class="ui-page-title">プロフィール設定</h1>

        <form novalidate method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data" class="ui-form">
            @csrf
            <div class="ui-avatar-row">
                @php
                    $avatarUrl = !empty($user->avatar_path) ? asset('storage/'.$user->avatar_path) : null;
                @endphp
                <div class="ui-avatar-preview">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                    @endif
                </div>
                <input id="avatar" type="file" name="avatar" accept="image/jpeg,image/png" class="sr-only" />
                <label for="avatar" class="ui-file-button">
                    画像を選択する
                </label>
                <p id="avatar-file-name" class="ui-file-name">選択されていません</p>
            </div>
            @error('avatar')
                <p class="ui-error">{{ $message }}</p>
            @enderror

            <div class="ui-field">
                <label>ユーザー名</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name ?? '') }}"
                    class="ui-input"
                />
                @error('name')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>郵便番号</label>
                <input
                    type="text"
                    name="postal_code"
                    value="{{ old('postal_code', $user->postal_code ?? '') }}"
                    class="ui-input"
                />
                @error('postal_code')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>住所</label>
                <input
                    type="text"
                    name="address"
                    value="{{ old('address', $user->address ?? '') }}"
                    class="ui-input"
                />
                @error('address')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="ui-field">
                <label>建物名</label>
                <input
                    type="text"
                    name="building"
                    value="{{ old('building', $user->building ?? '') }}"
                    class="ui-input"
                />
                @error('building')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="ui-button-primary">更新する</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('avatar');
            const fileName = document.getElementById('avatar-file-name');

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
