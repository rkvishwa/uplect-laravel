<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('submission_type', 16)->default('text');
            $table->json('allowed_mime_types')->nullable();
            $table->unsignedTinyInteger('max_file_size_mb')->default(10);
            $table->unsignedSmallInteger('pass_mark')->default(50);
            $table->unsignedSmallInteger('max_mark')->default(100);
            $table->boolean('is_required_for_certification')->default(false);
            $table->timestamp('due_at')->nullable();
            $table->longText('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_assignments');
    }
};
