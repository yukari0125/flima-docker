<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        Comment::query()->delete();

        $item = Item::first();
        $user = User::where('email', 'admin@example.com')->first();

        if ($item && $user) {
            Comment::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'comment' => 'こちらにコメントが入ります。',
            ]);
        }
    }
}
