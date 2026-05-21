<?php
// app/Enums/UserRole.php
namespace App\Enums;

enum UserRole: string
{
    case Admin  = 'admin';
    case Guru   = 'guru';
    case Siswa  = 'siswa';
    case Client = 'client';

    public function label(): string
    {
        return match($this) {
            self::Admin  => 'Administrator',
            self::Guru   => 'Guru',
            self::Siswa  => 'Siswa',
            self::Client => 'Client',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Admin  => 'red',
            self::Guru   => 'blue',
            self::Siswa  => 'green',
            self::Client => 'yellow',
        };
    }
}