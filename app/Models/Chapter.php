<?php

namespace App\Models;

use App\Models\Page;
use App\Models\Manga;
use App\Models\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    protected $touches = ['manga'];

    use HasFactory;
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function views()
    // {
    //     return $this->hasMany(View::class);
    // }
}
