<x-market-layout :title="__('商品購入')">
    <form
        id="purchase-form"
        method="POST"
        action="{{ route('purchase.store', ['item_id' => $item->id]) }}"
        data-stripe-convenience-url="{{ $stripeCheckoutUrlConvenience ?? '' }}"
        data-stripe-card-url="{{ $stripeCheckoutUrlCard ?? '' }}"
        class="mx-auto grid max-w-5xl gap-10 lg:grid-cols-[1fr_320px]"
    >
        @csrf
        <div class="space-y-8 text-sm">
            @if (!($canPurchase ?? true))
                <div class="rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-600">
                    {{ $cannotPurchaseReason }}
                </div>
            @endif

            @error('purchase')
                <div class="rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-600">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex items-center gap-6 border-b border-gray-200 pb-6">
                @php
                    $imageUrl = null;
                    if (!empty($item->image_path)) {
                        $imageUrl = \Illuminate\Support\Str::startsWith($item->image_path, 'http')
                            ? $item->image_path
                            : asset('storage/'.$item->image_path);
                    }
                @endphp
                <div class="h-20 w-20 overflow-hidden rounded-sm bg-gray-300/80">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                    @else
                        <div class="flex h-full items-center justify-center text-xs text-gray-700">商品画像</div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold">{{ $item->name }}</p>
                    <p class="mt-2 text-base font-semibold">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="border-b border-gray-200 pb-6">
                <p class="text-xs font-semibold text-gray-600">支払い方法</p>
                <select id="payment_method" name="payment_method" class="mt-3 w-56 rounded-sm border border-gray-300 px-3 py-2 text-xs">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い" @selected(old('payment_method') === 'コンビニ払い')>コンビニ払い</option>
                    <option value="カード支払い" @selected(old('payment_method') === 'カード支払い')>カード支払い</option>
                </select>
                @error('payment_method')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 border-b border-gray-200 pb-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-gray-600">配送先</p>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="text-xs text-sky-600">
                        変更する
                    </a>
                </div>
                <input type="hidden" name="address" value="{{ old('address', $address ?? '') }}" />
                <input type="hidden" name="postal_code" value="{{ old('postal_code', $postal_code ?? '') }}" />
                <input type="hidden" name="building" value="{{ old('building', $building ?? '') }}" />
                <div class="text-xs text-gray-700">
                    〒{{ old('postal_code', $postal_code ?? 'XXX-YYYY') }}<br />
                    {{ old('address', $address ?? 'ここには住所が表示が入ります') }}
                    @if (old('building', $building ?? null))
                        <br />{{ old('building', $building ?? '') }}
                    @endif
                </div>
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-sm border border-gray-300">
                <div class="flex items-center justify-between border-b border-gray-300 px-4 py-3 text-xs">
                    <span>商品代金</span>
                    <span class="font-semibold">¥{{ number_format($item->price) }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 text-xs">
                    <span>支払い方法</span>
                    <span id="selected_payment_method" class="font-semibold">{{ old('payment_method', '選択してください') }}</span>
                </div>
            </div>
            @if ($canPurchase ?? true)
                <button class="w-full rounded-sm bg-rose-500 py-3 text-sm font-semibold text-white hover:bg-rose-600">
                    購入する
                </button>
            @else
                <button type="button" disabled class="w-full cursor-not-allowed rounded-sm bg-gray-300 py-3 text-sm font-semibold text-white">
                    購入できません
                </button>
            @endif
        </div>
    </form>
    <script>
        const paymentMethod = document.getElementById('payment_method');
        const selectedPaymentMethod = document.getElementById('selected_payment_method');
        const purchaseForm = document.getElementById('purchase-form');

        if (paymentMethod && selectedPaymentMethod) {
            paymentMethod.addEventListener('change', (event) => {
                selectedPaymentMethod.textContent = event.target.value || '選択してください';
            });
        }

        if (purchaseForm && paymentMethod) {
            purchaseForm.addEventListener('submit', () => {
                const selected = paymentMethod.value;
                let stripeUrl = '';

                if (selected === 'コンビニ払い') {
                    stripeUrl = purchaseForm.dataset.stripeConvenienceUrl || '';
                } else if (selected === 'カード支払い') {
                    stripeUrl = purchaseForm.dataset.stripeCardUrl || '';
                }

                if (stripeUrl) {
                    window.open(stripeUrl, '_blank', 'noopener,noreferrer');
                }
            });
        }
    </script>
</x-market-layout>
