<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_deliverables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['file', 'link', 'image'])->default('file');
            $table->string('file_path')->nullable();   // untuk file & image
            $table->string('link_url')->nullable();    // untuk link
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('is_final')->default(false); // tandai ini hasil final
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_deliverables');
    }
};
