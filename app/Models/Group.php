<?php

namespace App\Models;

use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->hasMany(User::class);
    }
    // public function user()// tên function = tên bảng_id=> ví dụ belongsto với foreign key là user_id thì tên function phải là user
    public function createdByUser() // tên function = tên bảng_id=> ví dụ belongsto với foreign key là user_id thì tên function phải là user
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function actions()
    {
        return $this->belongsToMany(Action::class)->withTimestamps();
    }
    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot(['scope'])->withTimestamps();
    }
}
