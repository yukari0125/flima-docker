<x-market-layout :title="__('住所の変更')">
    <div class="address-edit-bg">
        <div class="address-edit-shell">
            <h1 class="address-edit-title">住所の変更</h1>

            <form novalidate method="POST" action="{{ route('purchase.address.update', ['item_id' => $item_id ?? 1]) }}" class="address-edit-form">
                @csrf
                <div class="address-edit-field">
                    <label class="address-edit-label">郵便番号</label>
                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code', $postal_code ?? '') }}"
                        class="address-edit-input"
                    />
                    @error('postal_code')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-edit-field">
                    <label class="address-edit-label">住所</label>
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address', $address ?? '') }}"
                        class="address-edit-input"
                    />
                    @error('address')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-edit-field">
                    <label class="address-edit-label">建物名</label>
                    <input
                        type="text"
                        name="building"
                        value="{{ old('building', $building ?? '') }}"
                        class="address-edit-input"
                    />
                    @error('building')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-edit-submit-wrap">
                    <button
                        type="submit"
                        class="address-edit-submit"
                    >
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-market-layout>
