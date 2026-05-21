<?php
// app/Models/TaskProgress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskProgress extends Model
{
    protected $table = 'task_progresses'; // ← tambahkan ini

    protected $fillable = [
        'task_id',
        'user_id',
        'description',
        'status_update',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}