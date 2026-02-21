<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
            'stripeCheckoutUrlConvenience' => config('services.stripe.checkout_url_convenience'),
            'stripeCheckoutUrlCard' => config('services.stripe.checkout_url_card'),
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

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $data['payment_method'],
            'postal_code' => $user->postal_code,
            'address' => $data['address'],
            'building' => $user->building,
            'price' => $item->price,
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
}
