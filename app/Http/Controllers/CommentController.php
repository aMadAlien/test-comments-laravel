<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user')->get()->toArray();

        $comments = CommentService::handleFiles($comments);
        $comments = CommentService::buildHierarchy($comments);

        return response()->json($comments, 200);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => $request->userId,
            'home_page' => $request->homePage,
            'text' => $request->text,
            'parent_id' => $request->replyTo ?? null,
            'file' => !is_null($request->file) ? CommentService::saveFile($request->file) : ''
        ]);

        return response()->json($comment, 200);
    }
}
