<?php
// app/Models/Task.php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id', 'assigned_to', 'title',
        'description', 'status', 'priority', 'deadline',
    ];

    protected function casts(): array
    {
        return [
            'status'   => TaskStatus::class,
            'deadline' => 'date',
        ];
    }

    public function project()    { return $this->belongsTo(Project::class); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function progresses() { return $this->hasMany(TaskProgress::class); }
    public function files()      { return $this->hasMany(TaskFile::class); }

    public function latestProgress()
    {
        return $this->hasOne(TaskProgress::class)->latestOfMany();
    }
}