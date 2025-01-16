<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    public function Answers(){
        return $this->hasMany(Answer::class);
    }
}
