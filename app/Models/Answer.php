<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable = [
        'id',
        'question_id',
        'description',
        'explanation',
        'is_right',
        'enabled',
        'position',
        'keyboard_key',
        'created_at',
        'updated_at'
    ];
    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
