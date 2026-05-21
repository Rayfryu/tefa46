<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'order_id', 'project_id', 'invoice_number',
        'amount', 'tax', 'total', 'due_date',
        'status', 'issued_by',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount'   => 'decimal:2',
            'tax'      => 'decimal:2',
            'total'    => 'decimal:2',
        ];
    }

    public function order()    { return $this->belongsTo(Order::class); }
    public function project()  { return $this->belongsTo(Project::class); }
    public function issuedBy() { return $this->belongsTo(User::class, 'issued_by'); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // Generate nomor invoice otomatis
    public static function generateNumber(): string
    {
        $prefix = 'INV-' . date('Ym') . '-';
        $last   = self::where('invoice_number', 'like', $prefix . '%')
                      ->orderByDesc('invoice_number')
                      ->value('invoice_number');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function statusBadge(): string
    {
        return match($this->status) {
            'unpaid'               => 'bg-red-500/10 text-red-400 border-red-500/20',
            'pending_confirmation' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            'paid'                 => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'cancelled'            => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
            default                => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'unpaid'               => 'Belum Dibayar',
            'pending_confirmation' => 'Menunggu Konfirmasi',
            'paid'                 => 'Lunas',
            'cancelled'            => 'Dibatalkan',
            default                => '-',
        };
    }
}