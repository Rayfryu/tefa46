<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM(
        'active',
        'revision',
        'waiting_approval',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM(
        'active',
        'revision',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'active'");
    }
};
