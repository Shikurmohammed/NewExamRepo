<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    public function module(){
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
    public function User(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    protected $fillable =['module_id', 'name', 'description'];
}
