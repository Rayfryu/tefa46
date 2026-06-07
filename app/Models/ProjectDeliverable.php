<?php
// app/Models/ProjectDeliverable.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDeliverable extends Model
{
    protected $fillable = [
        'project_id', 'uploaded_by', 'title', 'description',
        'type', 'file_path', 'link_url', 'original_name',
        'file_size', 'is_final',
    ];

    protected function casts(): array
    {
        return [
            'is_final' => 'boolean',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return match($this->type) {
            'link'  => $this->link_url,
            default => asset('storage/' . $this->file_path),
        };
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'link'  => '🔗',
            'image' => '🖼️',
            'file'  => '📦',
            default => '📎',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->file_size;
        $i     = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}