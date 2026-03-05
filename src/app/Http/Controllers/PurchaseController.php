<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function show(string $item_id): View
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $cannotPurchaseReason = $this->cannotPurchaseReason($item, (int) $user?->id);

        return view('purchase', [
            'item_id' => $item_id,
            'item' => $item,
            'postal_code' => $user?->postal_code,
            'address' => $user?->address,
            'building' => $user?->building,
            'canPurchase' => $cannotPurchaseReason === null,
            'cannotPurchaseReason' => $cannotPurchaseReason,
        ]);
    }

    public function address(string $item_id): View
    {
        $user = Auth::user();

        return view('address', [
            'item_id' => $item_id,
            'postal_code' => $user?->postal_code,
            'address' => $user?->address,
            'building' => $user?->building,
        ]);
    }

    public function store(PurchaseRequest $request, string $item_id): RedirectResponse
    {
        $data = $request->validated();
        $item = Item::findOrFail($item_id);
        $user = $request->user();
        $cannotPurchaseReason = $this->cannotPurchaseReason($item, (int) $user->id);

        if ($cannotPurchaseReason !== null) {
            throw ValidationException::withMessages([
                'purchase' => $cannotPurchaseReason,
            ]);
        }

        $stripeSecret = (string) config('services.stripe.secret_key', '');
        $checkoutUrl = null;
        if ($stripeSecret !== '') {
            $checkoutUrl = $this->createStripeCheckoutSessionUrl(
                item: $item,
                user: $user,
                paymentMethod: $data['payment_method'],
                address: $data['address'],
                postalCode: $user->postal_code,
                building: $user->building,
                stripeSecret: $stripeSecret,
            );

            if ($checkoutUrl === null) {
                throw ValidationException::withMessages([
                    'purchase' => '決済画面の起動に失敗しました。時間をおいて再度お試しください。',
                ]);
            }
        }

        if ($checkoutUrl !== null) {
            return redirect()->away($checkoutUrl);
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $data['payment_method'],
            'postal_code' => $user->postal_code,
            'address' => $data['address'],
            'building' => $user->building,
            'price' => $item->price,
            'status' => 'completed',
        ]);

        return redirect()->route('items.index')->with('status', 'purchase-submitted');
    }

    public function success(Request $request, string $item_id): RedirectResponse
    {
        $user = $request->user();
        $item = Item::findOrFail($item_id);
        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId === '') {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済情報の確認に失敗しました。']);
        }

        $stripeSecret = (string) config('services.stripe.secret_key', '');
        if ($stripeSecret === '') {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => 'Stripe設定が不足しているため購入を確定できません。']);
        }

        $session = $this->fetchStripeCheckoutSession($sessionId, $stripeSecret);
        if ($session === null) {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済情報の確認に失敗しました。']);
        }

        if (($session['payment_status'] ?? null) !== 'paid') {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済が完了していません。']);
        }

        $metadata = $session['metadata'] ?? [];
        if (!is_array($metadata)) {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済情報の形式が不正です。']);
        }

        if (($metadata['item_id'] ?? null) !== (string) $item->id || ($metadata['user_id'] ?? null) !== (string) $user->id) {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済情報が現在の購入情報と一致しません。']);
        }

        $paymentMethod = (string) ($metadata['payment_method'] ?? '');
        if ($paymentMethod === '') {
            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => '決済情報に支払い方法が含まれていません。']);
        }

        $existing = Purchase::where('item_id', $item->id)->first();
        if ($existing !== null) {
            if ($existing->user_id === $user->id) {
                return redirect()->route('items.index')->with('status', 'purchase-submitted');
            }

            return redirect()->route('purchase.show', ['item_id' => $item->id])
                ->withErrors(['purchase' => 'この商品は売り切れのため購入できません']);
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
            'postal_code' => $metadata['postal_code'] ?? $user->postal_code,
            'address' => $metadata['address'] ?? null,
            'building' => $metadata['building'] ?? $user->building,
            'price' => $item->price,
            'status' => 'completed',
        ]);

        return redirect()->route('items.index')->with('status', 'purchase-submitted');
    }

    public function updateAddress(AddressRequest $request, string $item_id): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $user->fill([
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'] ?? null,
        ])->save();

        return redirect()->route('purchase.show', ['item_id' => $item_id])->with('status', 'address-updated');
    }

    private function cannotPurchaseReason(Item $item, int $userId): ?string
    {
        if ($item->user_id === $userId) {
            return '自分が出品した商品は購入できません';
        }

        if (Purchase::where('item_id', $item->id)->exists()) {
            return 'この商品は売り切れのため購入できません';
        }

        return null;
    }

    private function createStripeCheckoutSessionUrl(
        Item $item,
        $user,
        string $paymentMethod,
        string $address,
        ?string $postalCode,
        ?string $building,
        string $stripeSecret,
    ): ?string
    {
        $paymentMethodTypes = match ($paymentMethod) {
            'コンビニ払い' => ['konbini'],
            'カード支払い' => ['card'],
            default => null,
        };

        if ($paymentMethodTypes === null) {
            return null;
        }

        $successUrl = route('purchase.success', ['item_id' => $item->id], absolute: true).'?session_id={CHECKOUT_SESSION_ID}';

        $cancelUrl = (string) config('services.stripe.checkout_cancel_url', '');
        if ($cancelUrl === '') {
            $cancelUrl = route('purchase.show', ['item_id' => $item->id], absolute: true);
        }

        $response = Http::asForm()
            ->withToken($stripeSecret)
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'payment_method_types' => $paymentMethodTypes,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => (int) $item->price,
                        'product_data' => [
                            'name' => $item->name,
                        ],
                    ],
                ]],
                'customer_email' => $user?->email,
                'metadata' => [
                    'item_id' => (string) $item->id,
                    'user_id' => (string) $user?->id,
                    'payment_method' => $paymentMethod,
                    'postal_code' => $postalCode ?? '',
                    'address' => $address,
                    'building' => $building ?? '',
                ],
            ]);

        if ($response->failed()) {
            logger()->warning('Failed to create Stripe checkout session', [
                'status' => $response->status(),
                'body' => $response->body(),
                'item_id' => $item->id,
                'user_id' => $user?->id,
                'payment_method' => $paymentMethod,
            ]);

            return null;
        }

        $url = $response->json('url');
        return is_string($url) && $url !== '' ? $url : null;
    }

    private function fetchStripeCheckoutSession(string $sessionId, string $stripeSecret): ?array
    {
        $response = Http::withToken($stripeSecret)
            ->get('https://api.stripe.com/v1/checkout/sessions/'.urlencode($sessionId));

        if ($response->failed()) {
            logger()->warning('Failed to retrieve Stripe checkout session', [
                'status' => $response->status(),
                'body' => $response->body(),
                'session_id' => $sessionId,
            ]);

            return null;
        }

        $json = $response->json();
        return is_array($json) ? $json : null;
    }
}
