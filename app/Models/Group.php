<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'groups';
    public $timestamps = false;

    public function users(){
        return $this->belongsToMany(User::class, 'user_groups')->withPivot('user_id', 'group_id');;
    }
    public function tests(){
        return $this->belongsTo(Test::class, 'test_groups')->withPivot('test_id','group_id');
    }
}
