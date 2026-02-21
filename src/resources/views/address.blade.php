<x-market-layout :title="__('住所の変更')">
    <div class="mx-auto max-w-xl text-center">
        <h1 class="text-2xl font-semibold tracking-wide">住所の変更</h1>

        <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item_id ?? 1]) }}" class="mt-12 space-y-10 text-left">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-900">郵便番号</label>
                <input
                    type="text"
                    name="postal_code"
                    value="{{ old('postal_code', $postal_code ?? '') }}"
                    class="mt-3 w-full rounded-md border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('postal_code')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900">住所</label>
                <input
                    type="text"
                    name="address"
                    value="{{ old('address', $address ?? '') }}"
                    class="mt-3 w-full rounded-md border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900">建物名</label>
                <input
                    type="text"
                    name="building"
                    value="{{ old('building', $building ?? '') }}"
                    class="mt-3 w-full rounded-md border border-gray-400 px-4 py-2 text-sm focus:border-gray-700 focus:outline-none"
                />
                @error('building')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-8 text-center">
                <button
                    type="submit"
                    class="inline-flex w-72 items-center justify-center rounded-md bg-rose-500 px-6 py-3 text-base font-semibold text-white hover:bg-rose-600"
                >
                    更新する
                </button>
            </div>
        </form>
    </div>
</x-market-layout>
