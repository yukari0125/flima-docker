<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(CommentRequest $request, string $item_id): RedirectResponse
    {
        $data = $request->validated();

        Comment::create([
            'user_id' => $request->user()->id,
            'item_id' => $item_id,
            'comment' => $data['comment'],
        ]);

        return back()->with('status', 'comment-submitted');
    }
}
