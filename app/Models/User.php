<?php
// app/Models/User.php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'image',
        'division_id',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
        ];
    }

    // Helper methods untuk cek role
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }
    public function isGuru(): bool
    {
        return $this->role === UserRole::Guru;
    }
    public function isSiswa(): bool
    {
        return $this->role === UserRole::Siswa;
    }
    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    // Relationships
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function projectsAsGuru()
    {
        return $this->hasMany(Project::class, 'pic_guru_id');
    }

    public function projectsAsSiswa()
    {
        return $this->belongsToMany(Project::class, 'project_members', 'student_id', 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'student_id');
    }
}
