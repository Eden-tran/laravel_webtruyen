<?php

namespace App\Models;

use App\Models\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    public function actions()
    {
        return $this->hasMany(Action::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }
}
