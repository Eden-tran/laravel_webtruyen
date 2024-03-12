<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
}
