<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'client_id', 'amount_paid',
        'payment_method', 'proof_file', 'payment_date',
        'confirmed_by', 'confirmed_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'payment_date'  => 'date',
            'confirmed_at'  => 'datetime',
            'amount_paid'   => 'decimal:2',
        ];
    }

    public function invoice()     { return $this->belongsTo(Invoice::class); }
    public function client()      { return $this->belongsTo(User::class, 'client_id'); }
    public function confirmedBy() { return $this->belongsTo(User::class, 'confirmed_by'); }

    public function statusBadge(): string
    {
        return match($this->status) {
            'pending'   => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            'confirmed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'rejected'  => 'bg-red-500/10 text-red-400 border-red-500/20',
            default     => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'rejected'  => 'Ditolak',
            default     => '-',
        };
    }
}