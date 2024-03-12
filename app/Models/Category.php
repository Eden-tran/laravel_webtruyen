<?php

namespace App\Models;

use App\Models\Manga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'active',
    ];
    public function mangas()
    {
        return $this->belongsToMany(Manga::class)->withTimestamps();
    }
}
