<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::get('/verification-guide', function (Request $request) {
    if ($request->user()?->hasVerifiedEmail()) {
        return redirect()->route('mypage.profile');
    }

    return view('auth.verification-guide');
})->middleware('auth')->name('verification.guide');

Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('items.comment');
Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('items.favorite.store');
Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'destroy'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('items.favorite.destroy');

Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('purchase.show');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('purchase.store');
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('purchase.address');
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('purchase.address.update');

Route::get('/sell', [SellController::class, 'create'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('sell.create');
Route::post('/sell', [SellController::class, 'store'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('sell.store');

Route::get('/mypage', [MypageController::class, 'index'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('mypage');
Route::get('/mypage/profile', [MypageController::class, 'editProfile'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('mypage.profile');
Route::post('/mypage/profile', [ProfileController::class, 'update'])
    ->middleware(['auth', 'verified:verification.guide'])
    ->name('mypage.profile.update');
