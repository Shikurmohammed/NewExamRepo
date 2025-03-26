<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'begin_time',
        'end_time',
        'duration',
        'ip_range',
        'result_to_user',
        'report_to_user',
        'score_right',
        'score_wrong',
        'score_unanswered',
        'max_score',

        'score_threshold',
        'random_questions_select',
        'random_questions_order',
        'questions_order_mode',
        'random_answers_select',
        'random_answers_order',
        'answers_order_mode',
        'comment_enabled',
        'menu_enabled',
        'noanswer_enabled',
        'mcma_radio',
        'repeatable',
        'mcma_partial_score',
        'logout_on_timeout',
        'password',
        'user_id',
        'isLocked',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'test_groups')->withPivot('test_id', 'group_id');
    }
}
