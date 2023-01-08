<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Post, Comment};

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $newComment = new Comment($request->validate([
            'body' => 'required|string',
        ]));

        if (!$post = Post::query()->find($postId)) {
            abort(404);
        }

        $newComment->user()->associate($this->authUser)
            ->commentable()->associate($post)
            ->save();

        return redirect()->back();
    }

    public function destroy(int $postId, int $commentId)
    {
        $post = Post::find($postId);

        $comment = $post->comments()->where('id', $commentId)->first();

        if (!($this->authUser->id == $comment->user->id || $this->authUser->isAdmin())) {
            abort(404);
        }

        $comment->delete();

        return redirect()->route('posts.show', ['id' => $postId]);
    }
}
