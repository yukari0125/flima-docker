<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\User;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        Purchase::query()->delete();

        $buyer = User::where('email', 'user1@example.com')->first();
        $item = Item::where('name', '腕時計')->first();

        if ($buyer && $item) {
            Purchase::create([
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'payment_method' => 'コンビニ払い',
                'postal_code' => $buyer->postal_code,
                'address' => $buyer->address,
                'building' => $buyer->building,
                'price' => $item->price,
                'status' => 'completed',
            ]);
        }
    }
}
