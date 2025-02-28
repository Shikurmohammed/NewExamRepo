<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = [
        'topic_id',
        'description',
        'explanation',
        'enabled',
        'type',
        'difficulty',
        'position',
        'timer',
        'fullscreen',
        'inline_answers',
        'auto_next',
        'created_by'
    ];
    public function Answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
