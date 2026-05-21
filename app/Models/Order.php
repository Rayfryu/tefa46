<?php
// app/Models/Order.php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'service_id',
        'title',
        'description',
        'requirements',
        'budget',
        'deadline_requested',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'approved_at' => 'datetime',
            'deadline_requested' => 'date',
            'budget' => 'decimal:2',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    // app/Models/Order.php — tambahkan relasi ini
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}