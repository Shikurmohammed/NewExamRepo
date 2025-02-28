<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    protected $fillable = ['name', 'enabled', 'user_id'];
    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function topics()
    {
        return $this->hasMany(Topic::class, 'module_id', 'id');
    }
    protected $guarded = [];
}
