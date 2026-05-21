<?php
// app/Enums/OrderStatus.php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';
    case Approved   = 'approved';
    case Rejected   = 'rejected';
    case InProgress = 'in_progress';
    case Revision   = 'revision';
    case Done       = 'done';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending    => 'Menunggu',
            self::Approved   => 'Disetujui',
            self::Rejected   => 'Ditolak',
            self::InProgress => 'Dikerjakan',
            self::Revision   => 'Revisi',
            self::Done       => 'Selesai',
            self::Cancelled  => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending    => 'amber',
            self::Approved   => 'blue',
            self::Rejected   => 'red',
            self::InProgress => 'indigo',
            self::Revision   => 'orange',
            self::Done       => 'emerald',
            self::Cancelled  => 'slate',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Pending    => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::Approved   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            self::Rejected   => 'bg-red-500/10 text-red-400 border-red-500/20',
            self::InProgress => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
            self::Revision   => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
            self::Done       => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::Cancelled  => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }
}