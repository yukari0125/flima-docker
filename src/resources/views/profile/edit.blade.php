<x-market-layout :title="__('プロフィール設定')">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-center text-2xl font-semibold">プロフィール設定</h1>

        <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data" class="mt-10 space-y-8 text-sm">
            @csrf
            <div class="flex items-center gap-6">
                @php
                    $avatarUrl = !empty($user->avatar_path) ? asset('storage/'.$user->avatar_path) : null;
                @endphp
                <div class="h-20 w-20 overflow-hidden rounded-full bg-gray-300/80">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                    @endif
                </div>
                <label class="inline-flex cursor-pointer items-center rounded-sm border border-rose-400 px-4 py-1 text-xs font-semibold text-rose-500">
                    画像を選択する
                    <input type="file" name="avatar" class="hidden" />
                </label>
            </div>
            @error('avatar')
                <p class="-mt-4 text-xs text-red-600">{{ $message }}</p>
            @enderror

            <div>
                <label class="block text-xs font-semibold text-gray-900">ユーザー名</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name ?? '') }}"
                    class="mt-2 w-full rounded-sm border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('name')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900">郵便番号</label>
                <input
                    type="text"
                    name="postal_code"
                    value="{{ old('postal_code', $user->postal_code ?? '') }}"
                    class="mt-2 w-full rounded-sm border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('postal_code')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900">住所</label>
                <input
                    type="text"
                    name="address"
                    value="{{ old('address', $user->address ?? '') }}"
                    class="mt-2 w-full rounded-sm border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900">建物名</label>
                <input
                    type="text"
                    name="building"
                    value="{{ old('building', $user->building ?? '') }}"
                    class="mt-2 w-full rounded-sm border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('building')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6 text-center">
                <button
                    type="submit"
                    class="inline-flex w-72 items-center justify-center rounded-sm bg-rose-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-rose-600"
                >
                    更新する
                </button>
            </div>
        </form>
    </div>
</x-market-layout>
