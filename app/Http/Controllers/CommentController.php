<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
class CommentController extends Controller
{
    public function index(Request $request)
    {
        $childComments = Comment::with('user:id,name')
                                ->whereNotNull('parent_id')
                                ->get()
                                ->toArray();

        $sort = $request->input('sort', 'created_at-asc');
        list($sortField, $sortDirection) = explode('-', $sort);
        $sortField = ($sortField === 'created_at') ? 'comments.'.$sortField : $sortField;

        $topLevelComments = Comment::with('user:id,name')
                                    ->select('comments.*', 'users.name')
                                    ->whereNull('parent_id')
                                    ->join('users', 'comments.user_id', '=', 'users.id')
                                    ->orderBy($sortField, $sortDirection)
                                    ->paginate(5);

        $allComments = array_merge($childComments, $topLevelComments->items());

        $comments = CommentService::handleFiles($allComments);
        $comments = CommentService::buildHierarchy($comments);

        return response()->json([
            'totalPages' => $topLevelComments->lastPage(),
            'currentPage' => $topLevelComments->currentPage(),
            'comments' => $comments
        ], 200);
    }

    public function store(CommentRequest $request)
    {
        $allowedTags = '<a><i><strong><code>';

        $comment = Comment::create([
            'user_id' => $request->userId,
            'home_page' => $request->homePage,
            'text' => strip_tags($request->text, $allowedTags),
            'parent_id' => $request->replyTo ?? null,
            'file' => !is_null($request->file) ? CommentService::saveFile($request->file) : ''
        ]);

        return response()->json($comment, 200);
    }
}
