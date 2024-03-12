<?php

namespace App\Models;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookmark extends Model
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
}
