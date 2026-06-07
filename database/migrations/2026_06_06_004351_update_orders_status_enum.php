<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
        'pending',
        'approved',
        'rejected',
        'waiting_payment',
        'paid',
        'in_progress',
        'revision',
        'done',
        'cancelled'
    ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
        'pending',
        'approved',
        'rejected',
        'in_progress',
        'revision',
        'done',
        'cancelled'
    ) NOT NULL DEFAULT 'pending'");
    }
};
