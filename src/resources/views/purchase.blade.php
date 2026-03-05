<x-market-layout :title="__('商品購入')">
    <div class="purchase-bg">
        <form novalidate
            id="purchase-form"
            method="POST"
            action="{{ route('purchase.store', ['item_id' => $item->id]) }}"
            class="purchase-page"
        >
        @csrf
            <div class="purchase-main">
            @if (!($canPurchase ?? true))
                <div class="purchase-alert">
                    {{ $cannotPurchaseReason }}
                </div>
            @endif

            @error('purchase')
                <div class="purchase-alert">
                    {{ $message }}
                </div>
            @enderror

            <div class="purchase-item">
                @php
                    $imageUrl = null;
                    if (!empty($item->image_path)) {
                        $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                            ? $item->image_path
                            : asset('storage/'.$item->image_path);
                    }
                @endphp
                <div class="purchase-item-image">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="purchase-item-image-img" />
                    @else
                        <div class="purchase-item-image-placeholder">商品画像</div>
                    @endif
                </div>
                <div class="purchase-item-meta">
                    <p class="purchase-item-name">{{ $item->name }}</p>
                    <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="purchase-section">
                <p class="purchase-section-title">支払い方法</p>
                <select id="payment_method" name="payment_method" class="purchase-select">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い" @selected(old('payment_method') === 'コンビニ払い')>コンビニ払い</option>
                    <option value="カード支払い" @selected(old('payment_method') === 'カード支払い')>カード支払い</option>
                </select>
                @error('payment_method')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="purchase-section purchase-address">
                <div class="purchase-address-head">
                    <p class="purchase-section-title">配送先</p>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="purchase-address-link">
                        変更する
                    </a>
                </div>
                <input type="hidden" name="address" value="{{ old('address', $address ?? '') }}" />
                <input type="hidden" name="postal_code" value="{{ old('postal_code', $postal_code ?? '') }}" />
                <input type="hidden" name="building" value="{{ old('building', $building ?? '') }}" />
                <div class="purchase-address-text">
                    〒{{ old('postal_code', $postal_code ?? 'XXX-YYYY') }}<br />
                    {{ old('address', $address ?? 'ここには住所が表示が入ります') }}
                    @if (old('building', $building ?? null))
                        <br />{{ old('building', $building ?? '') }}
                    @endif
                </div>
                @error('address')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>
            </div>

            <div class="purchase-side">
            <div class="purchase-summary">
                <div class="purchase-summary-row purchase-summary-border">
                    <span>商品代金</span>
                    <span class="purchase-summary-value">¥{{ number_format($item->price) }}</span>
                </div>
                <div class="purchase-summary-row">
                    <span>支払い方法</span>
                    <span id="selected_payment_method" class="purchase-summary-value">{{ old('payment_method', '選択してください') }}</span>
                </div>
            </div>
            @if ($canPurchase ?? true)
                <button class="purchase-submit">
                    購入する
                </button>
            @else
                <button type="button" disabled class="purchase-submit purchase-submit-disabled">
                    購入できません
                </button>
            @endif
            </div>
        </form>
    </div>
    <script>
        const paymentMethod = document.getElementById('payment_method');
        const selectedPaymentMethod = document.getElementById('selected_payment_method');

        if (paymentMethod && selectedPaymentMethod) {
            paymentMethod.addEventListener('change', (event) => {
                selectedPaymentMethod.textContent = event.target.value || '選択してください';
            });
        }
    </script>
</x-market-layout>
