<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;


class CommentService
{

    public static function buildHierarchy($comments, $parentId = null) {
        $result = [];
        foreach ($comments as $item) {
            if ($item['parent_id'] === $parentId) {
                $children = self::buildHierarchy($comments, $item['id']);
                if ($children) {
                    $item['replies'] = $children;
                }
                $result[] = $item;
            }
        }
        return $result;
    }

    public static function handleFiles($comments): array
    {
        return array_map(function ($comment) {
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
    }

    public static function saveFile($file): string
    {
        $savedFile = Storage::disk('data')->put('comments', $file);
        return str_replace("comments/", "", $savedFile);
    }
}
