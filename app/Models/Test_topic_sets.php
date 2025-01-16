<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test_topic_sets extends Model
{
    protected $table = 'test_topic_sets';

    protected $fillable = [
        'test_id',
        'type',
        'difficulty',
        'quantity',
    ];
    //
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'test_topics', 'test_topic_set_id', 'topic_id'); //subset_id=test_topic_set_id
    }
}
