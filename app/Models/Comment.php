<?php

namespace App\Models;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function tree($id)
    {
        $allComments = Comment::where('manga_id', $id)->get();
        $rootComments = $allComments->whereNull('parent_comment_id');
        self::formatTree($rootComments, $allComments);
        return $rootComments;
    }
    public static function formatTree($comments, $allComments)
    {
        foreach ($comments as $comment) {
            $comment->children = $allComments->where('parent_comment_id', $comment->id)->values();
            if ($comment->children->isNotEmpty()) {
                self::formatTree($comment->children, $allComments);
            }
        }
    }
}
