<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'user_id', 'parent_id', 'home_page', 'text',
    ];

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
