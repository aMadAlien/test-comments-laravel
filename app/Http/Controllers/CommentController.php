<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all()->toArray();
        $comments = array_map(function ($comment) {
            $extension = pathinfo($comment['file'], PATHINFO_EXTENSION);

            $filePath = Storage::disk('data')->path('/comments/'.$comment['file']);
            $file = file_get_contents($filePath);

            $comment['file'] = [
                'content' => $extension === 'txt'
                    ? $file
                    : 'data:image/'.$extension.';base64, ' . base64_encode($file ?? ''),
                'extension' => $extension
            ];

            return $comment;
        }, $comments);
        return response()->json($comments, 200);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => $request->userId,
            'home_page' => $request->homePage,
            'text' => $request->text,
            'parent_id' => $request->replyTo ?? null,
            'file' => !is_null($request->file) ? self::saveFile($request->file) : ''
        ]);

        return response()->json($comment, 200);
    }

    public static function saveFile($file): string
    {
        $savedFile = Storage::disk('data')->put('comments', $file);
        return str_replace("comments/", "", $savedFile);
    }

}
