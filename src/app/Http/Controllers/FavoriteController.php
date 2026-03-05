<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    public function store(string $item_id): RedirectResponse
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    public function destroy(string $item_id): RedirectResponse
    {
        Favorite::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}
