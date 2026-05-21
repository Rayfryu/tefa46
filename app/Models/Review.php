<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'task_progress_id',
        'reviewer_id',
        'note',
        'status',
    ];

    public function taskProgress()
    {
        return $this->belongsTo(TaskProgress::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}