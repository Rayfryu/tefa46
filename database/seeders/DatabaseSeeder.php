<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat divisi/jurusan
        $divisions = [
            ['name' => 'Rekayasa Perangkat Lunak', 'slug' => 'rpl'],
            ['name' => 'Desain Komunikasi Visual',  'slug' => 'dkv'],
            ['name' => 'Multimedia',                'slug' => 'multimedia'],
            ['name' => 'Teknik Komputer Jaringan',  'slug' => 'tkj'],
        ];
        foreach ($divisions as $div) {
            Division::create($div);
        }

        // Buat user default setiap role
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@tefa.sch.id',
            'password' => Hash::make('password'),
            'role'     => UserRole::Admin,
        ]);

        User::create([
            'name'        => 'Guru Demo',
            'email'       => 'guru@tefa.sch.id',
            'password'    => Hash::make('password'),
            'role'        => UserRole::Guru,
            'division_id' => 1,
        ]);

        User::create([
            'name'        => 'Siswa Demo 1',
            'email'       => 'siswa@tefa.sch.id',
            'password'    => Hash::make('password'),
            'role'        => UserRole::Siswa,
            'division_id' => 1,
        ]);

        User::create([
            'name'        => 'Siswa Demo 2',
            'email'       => 'siswa2@tefa.sch.id',
            'password'    => Hash::make('password'),
            'role'        => UserRole::Siswa,
            'division_id' => 2,
        ]);

        User::create([
            'name'     => 'Client Demo',
            'email'    => 'client@tefa.sch.id',
            'password' => Hash::make('password'),
            'role'     => UserRole::Client,
        ]);
    }
}