<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MypageController extends Controller
{
    public function index(Request $request): View
    {
        $page = $request->query('page');
        $user = $request->user();
        $items = collect();

        if ($page === 'buy') {
            $items = Purchase::with('item')
                ->where('user_id', $user->id)
                ->latest()
                ->get()
                ->pluck('item')
                ->filter();
        } else {
            $items = Item::where('user_id', $user->id)->latest()->get();
        }

        return view('mypage.index', [
            'page' => $page,
            'user' => $user,
            'items' => $items,
        ]);
    }

    public function editProfile(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
}
