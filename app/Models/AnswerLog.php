<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerLog extends Model
{

    use HasFactory;
    protected $table = 'answer_logs';  // Assuming the table name is answer_logs
    protected $primaryKey = 'id';  // Assuming the primary key is id
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
