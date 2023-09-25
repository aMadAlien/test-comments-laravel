<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::whereNull('parent_id')->with('replies')->get();
        return response()->json($comments, 200);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => $request->userId,
            'home_page' => $request->homePage,
            'text' => $request->text,
            'parent_id' => $request->replyTo ?? null
        ]);

        return response()->json($comment, 200);
    }
}
