<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestLog extends Model
{

    use HasFactory;
    protected $table = 'test_logs';  // Assuming the table name is test_logs
    protected $primaryKey = 'id';  // Assuming the primary key is id

    // Define relationships, fillable fields, etc.
    public function testUser()
    {
        return $this->belongsTo(TestUser::class, 'tests_users_id ', 'id');
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    public function logAnswers()
    {
        return $this->hasMany(AnswerLog::class, 'test_logs_id');
    }
}
