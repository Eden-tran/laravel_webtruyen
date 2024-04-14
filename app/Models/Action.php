<?php

namespace App\Models;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Action extends Model
{
    use HasFactory;
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
