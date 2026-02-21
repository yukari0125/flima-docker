<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function create(): View
    {
        $categories = Category::query()->pluck('name')->all();

        return view('sell', [
            'categories' => $categories,
        ]);
    }

    public function store(ExhibitionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $categoryIds = collect($data['category'])
            ->map(fn ($name) => Category::firstOrCreate(['name' => $name])->id)
            ->values();
        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'brand' => $request->input('brand'),
            'description' => $data['description'],
            'price' => $data['price'],
            'condition' => $data['condition'],
            'image_path' => $imagePath,
        ]);

        $item->categories()->sync($categoryIds->all());

        return back()->with('status', 'exhibition-submitted');
    }
}
