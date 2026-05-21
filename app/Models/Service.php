<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'division_id', 'name', 'slug', 'description',
        'price_start', 'price_end', 'duration_estimate',
        'thumbnail', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'price_start' => 'decimal:2',
            'price_end'   => 'decimal:2',
        ];
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}