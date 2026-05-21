<?php
// app/Enums/TaskStatus.php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo       = 'todo';
    case InProgress = 'in_progress';
    case Review     = 'review';
    case Done       = 'done';
    case Revision   = 'revision';

    public function label(): string
    {
        return match($this) {
            self::Todo       => 'Belum Mulai',
            self::InProgress => 'Dikerjakan',
            self::Review     => 'Review',
            self::Done       => 'Selesai',
            self::Revision   => 'Revisi',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Todo       => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
            self::InProgress => 'bg-brand-500/10 text-brand-400 border-brand-500/20',
            self::Review     => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::Done       => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::Revision   => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
        };
    }
}