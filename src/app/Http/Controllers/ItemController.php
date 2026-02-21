<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab');
        $keyword = trim((string) $request->query('keyword', ''));
        $isGuestMylist = $tab === 'mylist' && ! Auth::check();
        $itemsQuery = Item::query()
            ->withCount('purchases')
            ->latest();

        if ($keyword !== '') {
            $itemsQuery->where('name', 'like', '%'.$keyword.'%');
        }

        if (Auth::check()) {
            $itemsQuery->where('user_id', '!=', Auth::id());
        }

        $items = $itemsQuery->get();

        if ($tab === 'mylist' && Auth::check()) {
            $mylistQuery = Item::query()
                ->withCount('purchases')
                ->whereHas('favorites', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->latest();

            if ($keyword !== '') {
                $mylistQuery->where('name', 'like', '%'.$keyword.'%');
            }

            $items = $mylistQuery->get();
        } elseif ($tab === 'mylist') {
            $items = collect();
        }

        return view('items.index', [
            'tab' => $tab,
            'items' => $items,
            'isGuestMylist' => $isGuestMylist,
        ]);
    }

    public function show(string $item_id): View
    {
        $item = Item::with(['comments.user', 'categories'])
            ->withCount(['favorites', 'comments'])
            ->findOrFail($item_id);

        return view('item', [
            'item' => $item,
            'isFavorited' => Auth::check()
                ? $item->favorites()->where('user_id', Auth::id())->exists()
                : false,
        ]);
    }
}
