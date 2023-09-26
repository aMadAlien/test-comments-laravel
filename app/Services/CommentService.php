<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
            if (!$comment['file']) return $comment;

            $extension = pathinfo($comment['file'], PATHINFO_EXTENSION);

            $filePath = Storage::disk('data')->path('/comments/'.$comment['file']);
            $file = file_get_contents($filePath);

            $comment['file'] = [
                'content' => $extension === 'txt'
                    ? $file
                    : 'data:image/'.$extension.';base64, ' . base64_encode($file ?? ''),
                'extension' => $extension
            ];
            $comment['created_at'] = date('y.m.d', strtotime($comment['created_at']));
            return $comment;
        }, $comments);
    }

    public static function saveFile($file): string
    {
        $extension = $file->extension();
        $fileName = '';

        if ($extension === 'txt') {
            $fileName = Storage::disk('data')->put('comments', $file);
        } else {
            $fileName = 'comments/' . time() . $file->getClientOriginalName();
            Image::make($file)
                ->fit(320, 240) // size 320 width, 240 height
                ->encode('jpg', 100)
                ->save(storage_path().'/data/'.$fileName, 100);
        }

        return str_replace('comments/', '', $fileName);
    }
}
