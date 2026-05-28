<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'student_id',
        'project_id',
        'title',
        'description',
        'thumbnail',
        'links',
        'skills',
        'type',
        'is_published',
    ];

    protected $casts = [
        'skills' => 'array',
        'links' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationship

    // Pemilik portfolio (siswa)
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Project TEFA terkait (optional)
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // function bantuan
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : null;
    }
}
