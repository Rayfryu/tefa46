<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Active          = 'active';
    case Revision        = 'revision';
    case WaitingApproval = 'waiting_approval';
    case Completed       = 'completed';
    case Cancelled       = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Active          => 'Aktif',
            self::Revision        => 'Revisi',
            self::WaitingApproval => 'Menunggu Persetujuan',
            self::Completed       => 'Selesai',
            self::Cancelled       => 'Dibatalkan',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active          => 'bg-brand-500/10 text-brand-400 border-brand-500/20',
            self::Revision        => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
            self::WaitingApproval => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            self::Completed       => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::Cancelled       => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
        };
    }
}