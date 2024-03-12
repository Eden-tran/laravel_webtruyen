<?php

namespace App\Models;

use App\Models\Like;
use App\Models\View;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manga extends Model
{

    public function views()
    {
        return $this->hasManyThrough(View::class, Chapter::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chapters() //chapters
    {
        return $this->hasMany(Chapter::class)->orderBy('updated_at', 'desc');
    }
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
