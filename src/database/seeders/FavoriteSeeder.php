<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        Favorite::query()->delete();

        $user = User::where('email', 'user1@example.com')->first();
        $item = Item::first();

        if ($user && $item) {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }
    }
}
