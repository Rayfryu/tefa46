<?php
// app/Models/Project.php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'order_id', 'title', 'description', 'division_id',
        'pic_guru_id', 'status', 'start_date', 'end_date', 'progress',
    ];

    protected function casts(): array
    {
        return [
            'status'     => ProjectStatus::class,
            'start_date' => 'date',
            'end_date'   => 'date',
            'progress'   => 'integer',
        ];
    }

    public function order()      { return $this->belongsTo(Order::class); }
    public function division()   { return $this->belongsTo(Division::class); }
    public function picGuru()    { return $this->belongsTo(User::class, 'pic_guru_id'); }
    public function tasks()      { return $this->hasMany(Task::class); }
    public function portfolio()  { return $this->hasOne(Portfolio::class); }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'student_id')
                    ->withPivot('role_in_project', 'joined_at')
                    ->withTimestamps();
    }

    // Auto-hitung progress dari task yang done
    public function recalculateProgress(): void
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            $this->update(['progress' => 0]);
            return;
        }
        $done = $this->tasks()->where('status', 'done')->count();
        $this->update(['progress' => round(($done / $total) * 100)]);
    }
}